import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import '../models/livraison.dart';

class ApiException implements Exception {
  final String message;
  ApiException(this.message);

  @override
  String toString() => message;
}

// Token expiré ou invalide : on doit revenir au login
class SessionExpiredException extends ApiException {
  SessionExpiredException() : super('Session expirée, reconnectez-vous');
}

class ApiService {
  // Emulateur Android : 10.0.2.2 = le PC. Vrai telephone : IP locale du PC.
  static const baseUrl = String.fromEnvironment(
    'API_URL',
    defaultValue: 'http://10.0.2.2:8000/api',
  );
  final _storage = const FlutterSecureStorage();

  Future<Map<String, String>> _headers() async {
    final token = await _storage.read(key: 'token');
    return {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      if (token != null) 'Authorization': 'Bearer $token',
    };
  }

  // Envoie une requête avec gestion centralisée : réseau et token expiré
  Future<http.Response> _request(
    Future<http.Response> Function(Map<String, String> headers) call,
  ) async {
    http.Response res;
    try {
      res = await call(await _headers()).timeout(const Duration(seconds: 10));
    } catch (_) {
      throw ApiException('Impossible de joindre le serveur');
    }
    if (res.statusCode == 401) {
      await _storage.delete(key: 'token');
      throw SessionExpiredException();
    }
    return res;
  }

  String _message(http.Response res, String fallback) {
    try {
      final d = jsonDecode(res.body);
      if (d is Map && d['message'] is String) return d['message'];
    } catch (_) {}
    return fallback;
  }

  Future<void> login(String email, String password) async {
    http.Response res;
    try {
      res = await http
          .post(
            Uri.parse('$baseUrl/login'),
            headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/json',
            },
            body: jsonEncode({'email': email, 'password': password}),
          )
          .timeout(const Duration(seconds: 10));
    } catch (_) {
      throw ApiException('Impossible de joindre le serveur');
    }

    final data = jsonDecode(res.body);
    if (res.statusCode != 200) {
      throw ApiException(data['message'] ?? 'Erreur de connexion');
    }
    if (data['user']['role'] != 'chauffeur') {
      throw ApiException("Ce compte n'est pas un compte chauffeur");
    }
    await _storage.write(key: 'token', value: data['token']);
  }

  Future<void> logout() async {
    try {
      await http
          .post(Uri.parse('$baseUrl/logout'), headers: await _headers())
          .timeout(const Duration(seconds: 5));
    } catch (_) {
      // Même si le serveur est injoignable, on supprime le token local
    }
    await _storage.delete(key: 'token');
  }

  Future<bool> isLoggedIn() async => await _storage.read(key: 'token') != null;

  Future<List<Livraison>> getLivraisons() async {
    final res = await _request(
      (h) => http.get(Uri.parse('$baseUrl/mes-livraisons'), headers: h),
    );
    if (res.statusCode != 200) {
      throw ApiException(_message(res, 'Erreur ${res.statusCode}'));
    }
    final list = jsonDecode(res.body)['data'] as List;
    return list.map((e) => Livraison.fromJson(e)).toList();
  }

  Future<Livraison> updateStatut(int id, String statut) async {
    final res = await _request(
      (h) => http.patch(
        Uri.parse('$baseUrl/livraisons/$id/statut'),
        headers: h,
        body: jsonEncode({'statut': statut}),
      ),
    );
    if (res.statusCode != 200) {
      throw ApiException(_message(res, 'Impossible de changer le statut'));
    }
    return Livraison.fromJson(jsonDecode(res.body)['data']);
  }

  Future<Livraison> addNote(int id, String note) async {
    final res = await _request(
      (h) => http.post(
        Uri.parse('$baseUrl/livraisons/$id/note'),
        headers: h,
        body: jsonEncode({'note': note}),
      ),
    );
    if (res.statusCode != 200) {
      throw ApiException(_message(res, "Impossible d'ajouter la note"));
    }
    return Livraison.fromJson(jsonDecode(res.body)['data']);
  }
}