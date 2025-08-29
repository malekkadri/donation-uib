"use client"

import type React from "react"
import { createContext, useContext, useState, useEffect } from "react"

type Language = "en" | "fr"

type LanguageContextType = {
  language: Language
  setLanguage: (lang: Language) => void
  t: (key: string) => string
}

const translations = {
  en: {
    // Navigation
    "nav.home": "Home",
    "nav.donate": "Donate",
    "nav.leaderboard": "Leaderboard",
    "nav.about": "About Us",
    "nav.gallery": "Gallery",
    "nav.contact": "Contact",
    "nav.donateNow": "Donate Now",

    // Hero Section
    "hero.title": "Make a Difference with Your Donation",
    "hero.subtitle": "Support our cause and help us create a better future for communities in need.",
    "hero.donateButton": "Donate Now",
    "hero.learnButton": "Learn More",

    // Features Section
    "features.title": "Why Donate With Us?",
    "features.subtitle": "Your contribution makes a real impact in the lives of those who need it most.",
    "features.card1.title": "Support Communities",
    "features.card1.description":
      "Your donations directly support communities in need, providing essential resources and services.",
    "features.card2.title": "Join Our Donors",
    "features.card2.description": "Become part of our community of generous donors making a positive impact together.",
    "features.card3.title": "See Your Impact",
    "features.card3.description": "View our gallery to see the real-world impact of your donations on communities.",

    // Top Donors Section
    "donors.title": "Our Top Donors",
    "donors.subtitle": "We appreciate the generosity of our donors who make our work possible.",
    "donors.viewAll": "View Full Leaderboard",
    "donors.empty": "No donors found. Be the first to donate!",

    // CTA Section
    "cta.title": "Ready to Make a Difference?",
    "cta.subtitle": "Your donation, no matter how small, can have a significant impact on those in need.",
    "cta.button": "Donate Now",

    // Footer
    "footer.description": "Supporting communities and making a difference through your generous donations.",
    "footer.quickLinks": "Quick Links",
    "footer.contactUs": "Contact Us",
    "footer.rights": "All rights reserved.",

    // Donate Page
    "donatePage.title": "Make a Donation",
    "donatePage.description": "Your generous donation will help us continue our mission and support those in need.",

    // Donation Form
    "form.fullName": "Full Name",
    "form.email": "Email",
    "form.mobile": "Mobile Number (Optional)",
    "form.mobileDescription": "Must be a 10-digit number starting with 6-9",
    "form.amount": "Donation Amount",
    "form.country": "Country",
    "form.state": "State",
    "form.city": "City",
    "form.streetAddress": "Street Address (Optional)",
    "form.addToLeaderboard": "Add me to the leaderboard",
    "form.addToLeaderboardDescription": "Your name and donation amount will be displayed on our leaderboard.",
    "form.proceedToPayment": "Proceed to Payment",
    "form.processing": "Processing...",
    "form.validation.nameMin": "Full name must be at least 2 characters.",
    "form.validation.nameMax": "Full name must be at most 100 characters.",
    "form.validation.emailInvalid": "Please enter a valid email address.",
    "form.validation.mobileInvalid": "Please enter a valid mobile number.",
    "form.validation.amountMin": "Amount must be at least 1.",
    "form.validation.countryRequired": "Please select a country.",
    "form.validation.stateRequired": "Please select a state.",
    "form.validation.cityRequired": "Please select a city.",
    "form.error.checkoutFailed": "Failed to create checkout session:",
    "form.error.validationFailed": "Validation failed:",
    "form.error.emptyResponse": "Server returned an empty JSON object. Check Laravel logs for details.",
    "form.error.noUrl": "No checkout URL returned from API.",
    "form.error.unexpected": "An unexpected error occurred",
    "form.success.messageSent": "Your message has been sent successfully. We'll get back to you soon!",
    "form.sending": "Sending...",
    "form.sendMessage": "Send Message",
    "form.placeholder.yourName": "John Doe",
    "form.placeholder.yourEmail": "john.doe@example.com",
    "form.placeholder.mobile": "9876543210",
    "form.placeholder.message": "Enter your message here",
    "form.placeholder.streetAddress": "Enter your street address",
    "form.placeholder.selectCountry": "Select a country",
    "form.placeholder.selectState": "Select a state",
    "form.placeholder.selectCity": "Select a city",
    "form.loading": "Loading...",

    // Leaderboard Page
    "leaderboardPage.title": "Donation Leaderboard",
    "leaderboardPage.description": "We appreciate the generosity of our donors who make our work possible.",
    "leaderboard.table.rank": "Rank",
    "leaderboard.table.donor": "Donor",
    "leaderboard.table.location": "Location",
    "leaderboard.table.amount": "Amount",
    "leaderboard.table.date": "Date",
    "leaderboard.pagination.pageOf": "Page {currentPage} of {lastPage}",
    "leaderboard.location.anonymous": "Anonymous Location",

    // About Page
    "about.hero.title": "About UIB Donation",
    "about.hero.subtitle":
      "We are dedicated to making a positive impact in communities through your generous donations.",
    "about.mission.title": "Our Mission",
    "about.mission.p1":
      "UIB Donation was founded with a clear mission: to create a platform that connects generous donors with communities in need. We believe that everyone deserves access to essential resources and opportunities, regardless of their background or circumstances.",
    "about.mission.p2":
      "Through our transparent donation process, we ensure that your contributions directly impact the lives of those who need it most. We work closely with local organizations and community leaders to identify areas of greatest need and to implement sustainable solutions.",
    "about.mission.p3":
      "Our commitment to transparency means that you can see exactly how your donations are being used and the difference they are making. We believe that by working together, we can create lasting positive change in communities around the world.",
    "about.values.title": "Our Values",
    "about.values.transparency.title": "Transparency",
    "about.values.transparency.description":
      "We believe in complete transparency in how donations are collected and utilized.",
    "about.values.community.title": "Community",
    "about.values.community.description": "We prioritize building strong relationships with the communities we serve.",
    "about.values.impact.title": "Impact",
    "about.values.impact.description": "We focus on creating meaningful, sustainable change with every donation.",
    "about.team.title": "Our Team",
    "about.team.empty": "No team members found.",

    // Albums Page
    "gallery.title": "Our Gallery",
    "gallery.description": "Browse through our photo albums to see the impact of your donations.",
    "albums.noImages": "No images",
    "albums.photos": "{count} photos",
    "albums.pagination.pageOf": "Page {currentPage} of {lastPage}",
    "albums.empty": "No albums found.",

    // Album Detail Page
    "albumDetail.back": "Back to Albums",
    "albumDetail.notFound": "Album not found.",
    "albumDetail.noPhotos": "No photos in this album.",

    // Contact Page
    "contactPage.title": "Contact Us",
    "contactPage.description":
      "Have questions or feedback? We'd love to hear from you. Fill out the form below or use our contact information.",
    "contact.info.title": "Contact Information",
    "contact.info.address": "65 Avenue Habib Bourguiba\nTunis, Tunisia\n1000",
    "contact.info.phone": "+216 71 218 000",
    "contact.info.email": "contact@uib.com.tn",
    "contact.hours.title": "Office Hours",
    "contact.hours.monFri": "Monday - Friday:",
    "contact.hours.sat": "Saturday:",
    "contact.hours.sun": "Sunday:",
    "contact.followUs.title": "Follow Us",

    // Photo Gallery
    "photoGallery.close": "Close",
    "photoGallery.previous": "Previous",
    "photoGallery.next": "Next",
  },
  fr: {
    // Navigation
    "nav.home": "Accueil",
    "nav.donate": "Faire un don",
    "nav.leaderboard": "Classement",
    "nav.about": "À propos",
    "nav.gallery": "Galerie",
    "nav.contact": "Contact",
    "nav.donateNow": "Faire un don",

    // Hero Section
    "hero.title": "Faites la différence avec votre don",
    "hero.subtitle":
      "Soutenez notre cause et aidez-nous à créer un meilleur avenir pour les communautés dans le besoin.",
    "hero.donateButton": "Faire un don",
    "hero.learnButton": "En savoir plus",

    // Features Section
    "features.title": "Pourquoi faire un don avec nous?",
    "features.subtitle": "Votre contribution a un impact réel sur la vie de ceux qui en ont le plus besoin.",
    "features.card1.title": "Soutenir les communautés",
    "features.card1.description":
      "Vos dons soutiennent directement les communautés dans le besoin, fournissant des ressources et services essentiels.",
    "features.card2.title": "Rejoignez nos donateurs",
    "features.card2.description":
      "Faites partie de notre communauté de donateurs généreux qui font ensemble un impact positif.",
    "features.card3.title": "Voyez votre impact",
    "features.card3.description": "Consultez notre galerie pour voir l'impact réel de vos dons sur les communautés.",

    // Top Donors Section
    "donors.title": "Nos principaux donateurs",
    "donors.subtitle": "Nous apprécions la générosité de nos donateurs qui rendent notre travail possible.",
    "donors.viewAll": "Voir le classement complet",
    "donors.empty": "Aucun donateur trouvé. Soyez le premier à faire un don!",

    // CTA Section
    "cta.title": "Prêt à faire la différence?",
    "cta.subtitle": "Votre don, aussi petit soit-il, peut avoir un impact significatif sur ceux qui en ont besoin.",
    "cta.button": "Faire un don",

    // Footer
    "footer.description": "Soutenir les communautés et faire la différence grâce à vos généreux dons.",
    "footer.quickLinks": "Liens rapides",
    "footer.contactUs": "Contactez-nous",
    "footer.rights": "Tous droits réservés.",

    // Donate Page
    "donatePage.title": "Faire un don",
    "donatePage.description":
      "Votre don généreux nous aidera à poursuivre notre mission et à soutenir ceux qui en ont besoin.",

    // Donation Form
    "form.fullName": "Nom complet",
    "form.email": "E-mail",
    "form.mobile": "Numéro de portable (Facultatif)",
    "form.mobileDescription": "Doit être un numéro à 10 chiffres commençant par 6-9",
    "form.amount": "Montant du don",
    "form.country": "Pays",
    "form.state": "État",
    "form.city": "Ville",
    "form.streetAddress": "Adresse (Facultatif)",
    "form.addToLeaderboard": "M'ajouter au classement",
    "form.addToLeaderboardDescription": "Votre nom et le montant de votre don seront affichés dans notre classement.",
    "form.proceedToPayment": "Procéder au paiement",
    "form.processing": "Traitement...",
    "form.validation.nameMin": "Le nom complet doit contenir au moins 2 caractères.",
    "form.validation.nameMax": "Le nom complet doit contenir au plus 100 caractères.",
    "form.validation.emailInvalid": "Veuillez entrer une adresse e-mail valide.",
    "form.validation.mobileInvalid": "Veuillez entrer un numéro de portable valide.",
    "form.validation.amountMin": "Le montant doit être d'au moins 1.",
    "form.validation.countryRequired": "Veuillez sélectionner un pays.",
    "form.validation.stateRequired": "Veuillez sélectionner un état.",
    "form.validation.cityRequired": "Veuillez sélectionner une ville.",
    "form.error.checkoutFailed": "Échec de la création de la session de paiement:",
    "form.error.validationFailed": "Échec de la validation:",
    "form.error.emptyResponse":
      "Le serveur a renvoyé un objet JSON vide. Vérifiez les journaux Laravel pour plus de détails.",
    "form.error.noUrl": "Aucune URL de paiement renvoyée par l'API.",
    "form.error.unexpected": "Une erreur inattendue s'est produite",
    "form.success.messageSent": "Votre message a été envoyé avec succès. Nous vous répondrons bientôt !",
    "form.sending": "Envoi...",
    "form.sendMessage": "Envoyer le message",
    "form.placeholder.yourName": "Jean Dupont",
    "form.placeholder.yourEmail": "jean.dupont@example.com",
    "form.placeholder.mobile": "9876543210",
    "form.placeholder.message": "Entrez votre message ici",
    "form.placeholder.streetAddress": "Entrez votre adresse",
    "form.placeholder.selectCountry": "Sélectionner un pays",
    "form.placeholder.selectState": "Sélectionner un état",
    "form.placeholder.selectCity": "Sélectionner une ville",
    "form.loading": "Chargement...",

    // Leaderboard Page
    "leaderboardPage.title": "Classement des dons",
    "leaderboardPage.description": "Nous apprécions la générosité de nos donateurs qui rendent notre travail possible.",
    "leaderboard.table.rank": "Rang",
    "leaderboard.table.donor": "Donateur",
    "leaderboard.table.location": "Emplacement",
    "leaderboard.table.amount": "Montant",
    "leaderboard.table.date": "Date",
    "leaderboard.pagination.pageOf": "Page {currentPage} sur {lastPage}",
    "leaderboard.location.anonymous": "Emplacement anonyme",

    // About Page
    "about.hero.title": "À propos de UIB Donation",
    "about.hero.subtitle":
      "Nous nous engageons à avoir un impact positif sur les communautés grâce à vos dons généreux.",
    "about.mission.title": "Notre Mission",
    "about.mission.p1":
      "UIB Donation a été fondée avec une mission claire : créer une plateforme qui connecte les donateurs généreux avec les communautés dans le besoin. Nous croyons que chacun mérite l'accès aux ressources et opportunités essentielles, quelles que soient ses origines ou ses circonstances.",
    "about.mission.p2":
      "Grâce à notre processus de don transparent, nous nous assurons que vos contributions ont un impact direct sur la vie de ceux qui en ont le plus besoin. Nous travaillons en étroite collaboration avec les organisations locales et les leaders communautaires pour identifier les domaines les plus nécessiteux et mettre en œuvre des solutions durables.",
    "about.mission.p3":
      "Notre engagement envers la transparence signifie que vous pouvez voir exactement comment vos dons sont utilisés et la différence qu'ils font. Nous croyons qu'en travaillant ensemble, nous pouvons créer un changement positif durable dans les communautés du monde entier.",
    "about.values.title": "Nos Valeurs",
    "about.values.transparency.title": "Transparence",
    "about.values.transparency.description":
      "Nous croyons en une transparence totale sur la manière dont les dons sont collectés et utilisés.",
    "about.values.community.title": "Communauté",
    "about.values.community.description":
      "Nous privilégions l'établissement de relations solides avec les communautés que nous servons.",
    "about.values.impact.title": "Impact",
    "about.values.impact.description":
      "Nous nous concentrons sur la création d'un changement significatif et durable avec chaque don.",
    "about.team.title": "Notre Équipe",
    "about.team.empty": "Aucun membre de l'équipe trouvé.",

    // Albums Page
    "gallery.title": "Notre Galerie",
    "gallery.description": "Parcourez nos albums photo pour voir l'impact de vos dons.",
    "albums.noImages": "Aucune image",
    "albums.photos": "{count} photos",
    "albums.pagination.pageOf": "Page {currentPage} sur {lastPage}",
    "albums.empty": "Aucun album trouvé.",

    // Album Detail Page
    "albumDetail.back": "Retour aux albums",
    "albumDetail.notFound": "Album introuvable.",
    "albumDetail.noPhotos": "Aucune photo dans cet album.",

    // Contact Page
    "contactPage.title": "Contactez-nous",
    "contactPage.description":
      "Des questions ou des commentaires ? Nous serions ravis de vous entendre. Remplissez le formulaire ci-dessous ou utilisez nos coordonnées.",
    "contact.info.title": "Coordonnées",
    "contact.info.address": "65 Avenue Habib Bourguiba\nTunis, Tunisie\n1000",
    "contact.info.phone": "+216 71 218 000",
    "contact.info.email": "contact@uib.com.tn",
    "contact.hours.title": "Heures de bureau",
    "contact.hours.monFri": "Lundi - Vendredi :",
    "contact.hours.sat": "Samedi :",
    "contact.hours.sun": "Dimanche :",
    "contact.followUs.title": "Suivez-nous",

    // Photo Gallery
    "photoGallery.close": "Fermer",
    "photoGallery.previous": "Précédent",
    "photoGallery.next": "Suivant",
  },
}

const LanguageContext = createContext<LanguageContextType | undefined>(undefined)

export function LanguageProvider({ children }: { children: React.ReactNode }) {
  const [language, setLanguageState] = useState<Language>("en")

  useEffect(() => {
    // Check if there's a saved language preference in localStorage
    const savedLanguage = localStorage.getItem("language") as Language
    if (savedLanguage && (savedLanguage === "en" || savedLanguage === "fr")) {
      setLanguageState(savedLanguage)
    } else {
      // Check browser language
      const browserLanguage = navigator.language.split("-")[0]
      if (browserLanguage === "fr") {
        setLanguageState("fr")
      }
    }
  }, [])

  const setLanguage = (lang: Language) => {
    setLanguageState(lang)
    localStorage.setItem("language", lang)
  }

  const t = (key: string): string => {
    const translation = translations[language][key as keyof (typeof translations)[typeof language]]
    if (translation === undefined) {
      console.warn(`Missing translation for key: ${key} in language: ${language}`)
      return key // Fallback to key if translation is missing
    }
    return translation
  }

  return <LanguageContext.Provider value={{ language, setLanguage, t }}>{children}</LanguageContext.Provider>
}

export function useLanguage() {
  const context = useContext(LanguageContext)
  if (context === undefined) {
    throw new Error("useLanguage must be used within a LanguageProvider")
  }
  return context
}
