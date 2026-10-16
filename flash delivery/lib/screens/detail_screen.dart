import 'package:flutter/material.dart';
import '../models/livraison.dart';
import '../services/api_service.dart';
import '../widgets/statut_badge.dart';
import 'login_screen.dart';

class DetailScreen extends StatefulWidget {
  final Livraison livraison;
  const DetailScreen({super.key, required this.livraison});

  @override
  State<DetailScreen> createState() => _DetailScreenState();
}

class _DetailScreenState extends State<DetailScreen> {
  final _api = ApiService();
  late Livraison _l;
  final _noteCtrl = TextEditingController();
  bool _busy = false;

  @override
  void initState() {
    super.initState();
    _l = widget.livraison;
    _noteCtrl.text = _l.note ?? '';
  }

  @override
  void dispose() {
    _noteCtrl.dispose();
    super.dispose();
  }

  void _snack(String msg) {
    if (!mounted) return;
    ScaffoldMessenger.of(context)
      ..hideCurrentSnackBar()
      ..showSnackBar(SnackBar(content: Text(msg)));
  }

  void _goToLogin() {
    if (!mounted) return;
    Navigator.of(context).pushAndRemoveUntil(
      MaterialPageRoute(builder: (_) => const LoginScreen()),
      (route) => false,
    );
  }

  Future<void> _change(String statut) async {
    if (statut == 'livree') {
      final ok = await showDialog<bool>(
        context: context,
        builder: (ctx) => AlertDialog(
          title: const Text('Confirmer'),
          content: const Text('Marquer cette livraison comme livrée ?'),
          actions: [
            TextButton(
              onPressed: () => Navigator.pop(ctx, false),
              child: const Text('Annuler'),
            ),
            FilledButton(
              onPressed: () => Navigator.pop(ctx, true),
              child: const Text('Confirmer'),
            ),
          ],
        ),
      );
      if (ok != true) return;
    }

    setState(() => _busy = true);
    try {
      final updated = await _api.updateStatut(_l.id, statut);
      if (!mounted) return;
      setState(() => _l = updated);
      _snack('Statut mis à jour');
    } on SessionExpiredException {
      _goToLogin();
    } on ApiException catch (e) {
      _snack(e.message);
    } finally {
      if (mounted) setState(() => _busy = false);
    }
  }

  Future<void> _saveNote() async {
    final text = _noteCtrl.text.trim();
    if (text.isEmpty) {
      _snack('La note est vide');
      return;
    }
    FocusScope.of(context).unfocus();
    setState(() => _busy = true);
    try {
      final updated = await _api.addNote(_l.id, text);
      if (!mounted) return;
      setState(() => _l = updated);
      _snack('Note enregistrée');
    } on SessionExpiredException {
      _goToLogin();
    } on ApiException catch (e) {
      _snack(e.message);
    } finally {
      if (mounted) setState(() => _busy = false);
    }
  }

  Widget _row(String label, String value) => Padding(
        padding: const EdgeInsets.symmetric(vertical: 6),
        child: Row(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            SizedBox(
              width: 90,
              child: Text(label, style: const TextStyle(color: Colors.grey)),
            ),
            Expanded(child: Text(value)),
          ],
        ),
      );

  Widget _actionButton() {
    if (_l.statut == 'affectee') {
      return FilledButton.icon(
        onPressed: _busy ? null : () => _change('en_cours'),
        icon: const Icon(Icons.play_arrow),
        label: const Text('Démarrer la livraison'),
      );
    }
    if (_l.statut == 'en_cours') {
      return FilledButton.icon(
        style: FilledButton.styleFrom(backgroundColor: Colors.green),
        onPressed: _busy ? null : () => _change('livree'),
        icon: const Icon(Icons.check),
        label: const Text('Marquer comme livrée'),
      );
    }
    return const Center(
      child: Text(
        'Livraison terminée ✔',
        style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Livraison #${_l.id}')),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          Card(
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      const Text('Statut', style: TextStyle(color: Colors.grey)),
                      const SizedBox(width: 12),
                      StatutBadge(statut: _l.statut),
                    ],
                  ),
                  const Divider(height: 24),
                  _row('Client', _l.client),
                  _row('Téléphone', _l.telephone),
                  _row('Adresse', _l.adresse),
                  _row('Date', _l.date),
                  if (_l.details != null && _l.details!.isNotEmpty)
                    _row('Détails', _l.details!),
                ],
              ),
            ),
          ),
          const SizedBox(height: 16),
          _actionButton(),
          const SizedBox(height: 24),
          const Text('Note', style: TextStyle(fontWeight: FontWeight.bold)),
          const SizedBox(height: 8),
          TextField(
            controller: _noteCtrl,
            maxLines: 3,
            maxLength: 1000,
            decoration: const InputDecoration(
              hintText: 'Ex : client absent, rappel prévu',
              border: OutlineInputBorder(),
            ),
          ),
          const SizedBox(height: 8),
          OutlinedButton.icon(
            onPressed: _busy ? null : _saveNote,
            icon: const Icon(Icons.save),
            label: const Text('Enregistrer la note'),
          ),
          if (_busy) ...[
            const SizedBox(height: 16),
            const Center(child: CircularProgressIndicator()),
          ],
        ],
      ),
    );
  }
}