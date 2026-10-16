import 'package:flutter/material.dart';

class StatutBadge extends StatelessWidget {
  final String statut;
  const StatutBadge({super.key, required this.statut});

  @override
  Widget build(BuildContext context) {
    Color bg;
    Color fg;
    switch (statut) {
      case 'affectee':
        bg = Colors.blue.shade100;
        fg = Colors.blue.shade900;
        break;
      case 'en_cours':
        bg = Colors.orange.shade100;
        fg = Colors.orange.shade900;
        break;
      case 'livree':
        bg = Colors.green.shade100;
        fg = Colors.green.shade900;
        break;
      default:
        bg = Colors.grey.shade300;
        fg = Colors.grey.shade900;
    }
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
      decoration: BoxDecoration(color: bg, borderRadius: BorderRadius.circular(12)),
      child: Text(
        statut.replaceAll('_', ' '),
        style: TextStyle(color: fg, fontSize: 12, fontWeight: FontWeight.w600),
      ),
    );
  }
}