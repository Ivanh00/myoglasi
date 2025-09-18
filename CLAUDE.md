# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Development Commands

**Start development environment:**
```bash
composer dev
```
This runs Laravel server, queue worker, Pail logs, and Vite concurrently.

**Individual services:**
```bash
php artisan serve          # Laravel development server
php artisan queue:listen    # Queue worker for background jobs
php artisan pail           # Real-time log viewer
npm run dev               # Vite development server
```

**Testing:**
```bash
composer test             # Run Pest test suite
php artisan test         # Alternative test command
```

**Building:**
```bash
npm run build            # Build frontend assets for production
```

**Database:**
```bash
php artisan migrate      # Run database migrations
php artisan migrate:fresh --seed  # Fresh database with seeders
```

**Code formatting:**
```bash
./vendor/bin/pint        # Format PHP code using Laravel Pint
```

## Architecture Overview

### Technology Stack
- **Backend**: Laravel 12.0 with PHP 8.2+
- **Frontend**: Livewire 3.6 + Volt 1.7 for reactive components
- **Styling**: TailwindCSS 3.1 with custom Figtree font
- **JavaScript**: Alpine.js 3.14 for client-side interactivity
- **Database**: SQLite (development)
- **Testing**: Pest 4.0 framework
- **Real-time**: Pusher WebSocket integration
- **Build Tool**: Vite 7.0

### Application Domain
This is a classified ads platform (`PazAriO` = "my ads" in Serbian) with:
- User-to-user messaging system
- Transaction/balance system with posting fees
- Multi-image listing support
- Real-time notifications
- Admin content management
- Favorites and search functionality

### Key Architectural Patterns

**Livewire-Heavy Architecture**: The application uses 58+ Livewire components for most UI interactions. When working with frontend functionality, look for existing Livewire components in `app/Livewire/` before creating new ones.

**Component Structure**:
- `app/Livewire/` - Main UI logic and state management
- `resources/views/livewire/` - Livewire component templates
- `resources/views/components/` - Reusable Blade components

**Real-time Features**: Uses Pusher for WebSocket connections. Event classes in `app/Events/` trigger real-time updates for messaging and notifications.

**File Management**: 
- User avatars handled by dedicated upload system
- Listing images support multiple files per listing
- Uses Laravel's file storage with organized directory structure

**Financial System**: Built-in transaction system with user balances and configurable fees for ad posting.

### Core Models & Relationships

**Primary Models**:
- `User` - Enhanced with balance, phone visibility, avatar
- `Listing` - Main ads with multiple images, categories, conditions  
- `Message` - User-to-user messaging with read status
- `Transaction` - Financial operations and fee tracking
- `Category` - Hierarchical ad categorization
- `Favorite` - User saved listings

**Key Relationships**:
- Users have many listings, messages, transactions, favorites
- Listings belong to users and categories, have many images
- Messages create conversations between users about specific listings

### Development Patterns

**Livewire Components**: Most user interactions are handled by Livewire components rather than traditional controllers. Look in `app/Livewire/` for existing components before creating new functionality.

**Admin Functionality**: Admin components are separated and prefixed (e.g., `Admin/`). Admin routes and policies handle authorization.

**Testing**: Uses Pest with `RefreshDatabase` trait. Feature tests focus on user workflows rather than unit tests.

**Localization**: Multi-language support structure exists in `resources/lang/`. Use localization keys for user-facing text.

**Queue System**: Background jobs handle time-intensive operations like image processing and notifications. Queue worker must be running during development.

## Important Files & Directories

**Configuration**:
- `config/pusher.php` - Real-time WebSocket configuration
- `tailwind.config.js` - Custom TailwindCSS setup with content paths
- `vite.config.js` - Frontend build configuration

**Key Directories**:
- `app/Livewire/` - 58+ reactive components (primary UI logic)
- `app/Services/` - Business logic services
- `app/Policies/` - Authorization logic
- `resources/views/livewire/` - Livewire component templates
- `database/migrations/` - 16 migration files for schema

**Entry Points**:
- `routes/web.php` - Main application routes (heavily uses Livewire)
- `resources/views/layouts/app.blade.php` - Main application layout
- `resources/js/app.js` - Frontend JavaScript entry point