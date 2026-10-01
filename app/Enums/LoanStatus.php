<?php

// L'adresse de la classe : dossier app/Enums
namespace App\Enums;

// enum = liste de valeurs fixes (comme en Java)
// ": string" = chaque valeur est enregistrée comme du texte dans la base
enum LoanStatus: string
{
    case EnCours = 'en_cours';    // le livre est chez le membre
    case Rendu = 'rendu';         // le livre est revenu
    case EnRetard = 'en_retard';  // la date limite est dépassée
}