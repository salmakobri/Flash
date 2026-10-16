class Livraison {
  final int id;
  final String client, telephone, adresse, date, statut;
  final String? note, details;

  Livraison({
    required this.id,
    required this.client,
    required this.telephone,
    required this.adresse,
    required this.date,
    required this.statut,
    this.note,
    this.details,
  });

  factory Livraison.fromJson(Map<String, dynamic> j) => Livraison(
        id: j['id'],
        client: j['client'],
        telephone: j['telephone'],
        adresse: j['adresse'],
        date: j['date'],
        statut: j['statut'],
        note: j['note'],
        details: j['details'],
      );
}