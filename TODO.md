# PGN-Viewer

## Offene Aufgaben

Zurzeit keine.

## Erledigt

* **Viewer bleibt leer, wenn Contao die Skripte in den Seitenkopf stellt.**
  Behoben in Fassung 2.0.3: Die drei Skripte kommen jetzt über `TL_BODY` ans
  Ende des Body statt über `TL_JAVASCRIPT` in den Seitenkopf. Der im Bericht
  vorgeschlagene Weg über das Flag `|defer` wäre zu kurz gesprungen — den kennt
  nur Contao 5, in Contao 4.13 deutet `StringUtil::resolveFlaggedUrl()` ihn als
  Media-Angabe und ignoriert ihn.
  Gemeldet am 2026-08-08 aus der Sitzung zum BSV-Theme, Bericht in
  [BUGREPORT.md](BUGREPORT.md).
