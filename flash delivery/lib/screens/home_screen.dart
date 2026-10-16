import 'package:flutter/material.dart';
import '../models/livraison.dart';
import '../services/api_service.dart';
import '../widgets/statut_badge.dart';
import 'detail_screen.dart';
import 'login_screen.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  final _api = ApiService();
  late Future<List<Livraison>> _future;

  @override
  void initState() {
    super.initState();
    _future = _load();
  }

  Future<List<Livraison>> _load() async {
    try {
      return await _api.getLivraisons();
    } on SessionExpiredException {
      _goToLogin();
      return <Livraison>[];
    }
  }

  void _goToLogin() {
    if (!mounted) return;
    Navigator.of(context).pushReplacement(
      MaterialPageRoute(builder: (_) => const LoginScreen()),
    );
  }

  Future<void> _refresh() async {
    setState(() => _future = _load());
    try {
      await _future;
    } catch (_) {}
  }

  Future<void> _logout() async {
    await _api.logout();
    _goToLogin();
  }

  Future<void> _open(Livraison l) async {
    await Navigator.of(context).push(
      MaterialPageRoute(builder: (_) => DetailScreen(livraison: l)),
    );
    if (mounted) _refresh();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Mes livraisons'),
        actions: [
          IconButton(
            icon: const Icon(Icons.logout),
            tooltip: 'Déconnexion',
            onPressed: _logout,
          ),
        ],
      ),
      body: RefreshIndicator(
        onRefresh: _refresh,
        child: FutureBuilder<List<Livraison>>(
          future: _future,
          builder: (context, snap) {
            if (snap.connectionState != ConnectionState.done) {
              return const Center(child: CircularProgressIndicator());
            }
            if (snap.hasError) {
              return ListView(
                physics: const AlwaysScrollableScrollPhysics(),
                padding: const EdgeInsets.all(32),
                children: [
                  const SizedBox(height: 80),
                  const Icon(Icons.cloud_off, size: 56, color: Colors.grey),
                  const SizedBox(height: 12),
                  Text(
                    snap.error.toString(),
                    textAlign: TextAlign.center,
                    style: const TextStyle(fontSize: 16),
                  ),
                  const SizedBox(height: 16),
                  Center(
                    child: FilledButton(
                      onPressed: _refresh,
                      child: const Text('Réessayer'),
                    ),
                  ),
                ],
              );
            }
            final items = snap.data!;
            if (items.isEmpty) {
              return ListView(
                physics: const AlwaysScrollableScrollPhysics(),
                children: const [
                  SizedBox(height: 120),
                  Icon(Icons.inbox, size: 56, color: Colors.grey),
                  SizedBox(height: 12),
                  Center(child: Text('Aucune livraison pour le moment')),
                ],
              );
            }
            return ListView.builder(
              physics: const AlwaysScrollableScrollPhysics(),
              padding: const EdgeInsets.all(12),
              itemCount: items.length,
              itemBuilder: (context, i) {
                final l = items[i];
                return Card(
                  child: ListTile(
                    onTap: () => _open(l),
                    title: Text(
                      l.client,
                      style: const TextStyle(fontWeight: FontWeight.bold),
                    ),
                    subtitle: Padding(
                      padding: const EdgeInsets.only(top: 4),
                      child: Text('${l.adresse}\n${l.date}'),
                    ),
                    isThreeLine: true,
                    trailing: StatutBadge(statut: l.statut),
                  ),
                );
              },
            );
          },
        ),
      ),
    );
  }
} 