# 🎮 WooW – Jeu d’Aventure RPG Solo

WooW est un jeu web solo de type RPG développé avec **Laravel** (backend) et **Vue 3 + Vite** (frontend). Le joueur incarne un héros humain qui explore une carte 5x5, affronte des monstres, récupère des objets, gagne de l'expérience et tente de rejoindre la sortie sans mourir.

---

## 🧠 Fonctionnalités

- ✅ Inscription / Connexion via **Laravel Breeze**
- ✅ Création d’un personnage (race/classe verrouillées)
- ✅ Carte 5x5 générée aléatoirement
- ✅ Mouvements sur la carte via clics ou flèches
- ✅ Combats au tour par tour contre des monstres
- ✅ Système de loot avec objets bonus (HP, puissance, armure)
- ✅ Équipement d'objets avec effet direct sur les statistiques
- ✅ Suppression du personnage et recommencement
- ✅ Sauvegarde des données (map, position, objets) en base de données
- ✅ Données injectées depuis Laravel via `window.APP_PAYLOAD`

---

## 🛠️ Installation

### 1. Cloner le projet

```bash
git clone https://github.com/ton-utilisateur/woow-rpg.git
cd woow-rpg
```

### 2. Installer les dépendances PHP et JS

```bash
composer install
npm install
```

### 3. Configurer l’environnement

Copiez le fichier `.env` et configurez :

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Migrer la base de données

```bash
php artisan migrate
```

### 5. Compiler les assets

```bash
npm run dev
```

---

## 🚀 Lancer l'application

```bash
php artisan serve
```

Ouvrez [http://127.0.0.1:8000](http://127.0.0.1:8000) dans votre navigateur.

---

## 📁 Structure

| Dossier / Fichier             | Rôle                                              |
|------------------------------|---------------------------------------------------|
| `resources/js/pages/`        | Pages Vue (`home.vue`, `dashboard.vue`, etc.)    |
| `resources/js/components/`   | Composants Vue (`NavBar.vue`, etc.)              |
| `resources/views/app.blade.php` | Entrée unique SPA et injection des données     |
| `app/Http/Controllers/`      | Contrôleurs Laravel                              |
| `routes/web.php`             | Routes Laravel (auth, SPA, dashboard...)         |
| `public/json/items.json`     | Liste d’objets lootables                         |
| `public/json/monsters.json`  | Liste de monstres                                |

---

## 📦 Technologies

- **Laravel 10**
- **Vue 3 + Composition API**
- **Vite** pour le bundling
- **Tailwind CSS** via classes `@apply`
- **Laravel Breeze** pour l’authentification


---

## 🧑‍💻 Auteur

Projet développé par **Alex Hungerbühler** dans le cadre du module *Développement Produit Multimédia* à la HEIG-VD.

---

## 📝 Licence

Ce projet est open-source, sous licence [MIT](https://opensource.org/licenses/MIT).
