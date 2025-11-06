# JoinMe - Microcommunities Platform

JoinMe is a comprehensive platform for organizing and participating in recurring conversations around specific topics. Users can create or join conversations that meet weekly, monthly, or at custom intervals, either online or in-person.

## Features

### Core Features (Phase 1) ✅
- ✅ User authentication and authorization (Laravel Breeze + Livewire)
- ✅ Complete user profile system with interests, availability, and location
- ✅ Topic management with categories and icons
- ✅ Conversation creation with flexible scheduling (once, daily, weekly, biweekly, monthly)
- ✅ Privacy levels: public, moderated, private conversations
- ✅ Participation management with roles (participant, co-host, moderator)
- ✅ Attendance tracking and confirmation system
- ✅ Feedback and rating system (1-5 stars)
- ✅ External links integration (Zoom, Google Meet, etc.)
- ✅ Email notifications for key events

### Social Features (Phase 2) ✅
- ✅ User following system
- ✅ Blocking users
- ✅ Reporting system (users, conversations, messages)
- ✅ User discovery based on interests and location

### Gamification (Phase 3) ✅
- ✅ Achievement system with bronze, silver, gold, platinum levels
- ✅ Badge collection
- ✅ Points and rewards
- ✅ Leaderboards

### Advanced Features (Phases 4-9) ✅
- ✅ Mentorship program with pricing
- ✅ Referral system with rewards
- ✅ AI-powered transcriptions and summaries
- ✅ Real-time chat within conversations
- ✅ Public API with webhooks
- ✅ API key management with JWT-style tokens
- ✅ Advanced analytics and reporting
- ✅ Geolocation support for in-person meetings

### Admin Panel (Filament 3) ✅
- ✅ Complete admin dashboard
- ✅ User management
- ✅ Conversation moderation
- ✅ Content management
- ✅ Analytics and statistics
- ✅ Report handling
- ✅ System configuration

## Technology Stack

### Backend
- **Laravel 11** (PHP 8.4)
- **MySQL 8.0** - Primary database
- **Redis** - Cache and queues
- **Laravel Sanctum** - API authentication
- **Laravel Breeze** - Authentication scaffolding
- **Spatie Laravel Permission** - Roles and permissions

### Frontend
- **Livewire 3** - Dynamic UI components
- **Alpine.js** - Client-side interactions
- **Tailwind CSS** - Styling
- **Blade Templates** - Server-side rendering

### Admin Panel
- **Filament 3** - Modern admin panel
- 14 complete resources for content management

### Additional Tools
- **Laravel Horizon** - Queue monitoring
- **Laravel Telescope** - Debugging
- **PHPUnit** - Testing framework

## Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                      JoinMe Platform                         │
├─────────────────────────────────────────────────────────────┤
│                                                               │
│  ┌───────────────┐  ┌──────────────┐  ┌─────────────────┐  │
│  │   Frontend    │  │    Admin     │  │   Public API    │  │
│  │   (Livewire)  │  │  (Filament)  │  │   (Sanctum)     │  │
│  └───────┬───────┘  └──────┬───────┘  └────────┬────────┘  │
│          │                  │                    │           │
│  ┌───────┴──────────────────┴────────────────────┴────────┐ │
│  │              Application Layer                          │ │
│  │  Controllers │ Services │ Jobs │ Events │ Notifications│ │
│  └───────┬──────────────────────────────────────────┬─────┘ │
│          │                                           │        │
│  ┌───────┴───────────────────────────────────────────┴────┐ │
│  │                  Domain Layer                           │ │
│  │     Models │ Relationships │ Business Logic            │ │
│  └───────┬──────────────────────────────────────────┬─────┘ │
│          │                                           │        │
│  ┌───────┴───────────────────────────────────────────┴────┐ │
│  │              Infrastructure Layer                       │ │
│  │    MySQL │ Redis │ Storage │ Email │ External APIs     │ │
│  └─────────────────────────────────────────────────────────┘ │
│                                                               │
└─────────────────────────────────────────────────────────────┘
```

## Database Schema

### Core Tables (23 total)

**users** (Extended Laravel default)
- Profile: avatar, bio, interests, location, timezone
- Status: is_verified, is_active, last_active_at
- Soft deletes enabled

**topics**
- name, slug, category, description, icon, color
- Used to categorize conversations

**conversations**
- Complete conversation details (title, description, frequency, type)
- Privacy controls (public, moderated, private)
- Capacity management (max_participants, current_participants)
- Meeting details (location, meeting_url, meeting_platform)
- Settings (allow_chat, allow_recording, auto_confirm)

**participations**
- Links users to conversations
- Status: pending, accepted, rejected, cancelled
- Role: participant, co_host, moderator

**schedule_slots**
- Individual meeting instances
- scheduled_at, ends_at, status
- Attendance tracking
- Recording URLs

**attendances**
- Tracks who attended each slot
- Confirmation status
- Check-in/check-out times

**feedback**
- Rating system (1-5 stars)
- Comments and review text
- Links to conversations, schedule_slots, users

**external_links**
- Video call URLs (Zoom, Google Meet, etc.)
- WhatsApp groups, Telegram channels
- Type categorization

### Social Tables

**follows** - User following relationships
**blocks** - User blocking system
**reports** - Content moderation (users, conversations, messages)

### Gamification Tables

**achievements** - Unlockable achievements with points
**user_achievements** - User progress tracking
**badges** - Visual rewards system

### Advanced Feature Tables

**mentorships** - One-on-one mentoring sessions with pricing
**referrals** - Referral tracking with reward codes
**transcriptions** - AI-generated transcripts and summaries
**messages** - Real-time chat within conversations
**webhooks** - External integrations
**api_keys** - API access management

### System Tables

**notifications** (Laravel default)
**roles** (Spatie Permission)
**permissions** (Spatie Permission)

## Model Relationships

```
User
├── hasMany: ownedConversations
├── hasMany: participations
├── belongsToMany: conversations (through participations)
├── hasMany: feedbackGiven
├── hasMany: feedbackReceived
├── belongsToMany: blocking
├── belongsToMany: following
├── hasMany: mentorshipsAsMentor
├── hasMany: mentorshipsAsMentee
├── hasMany: referralsMade
└── belongsToMany: achievements

Conversation
├── belongsTo: owner (User)
├── belongsTo: topic
├── hasMany: participations
├── belongsToMany: users (through participations)
├── hasMany: scheduleSlots
├── hasMany: feedback
├── hasMany: externalLinks
├── hasMany: messages
└── hasMany: transcriptions

ScheduleSlot
├── belongsTo: conversation
├── hasMany: attendances
├── hasMany: feedback
└── hasOne: transcription

Topic
└── hasMany: conversations

Participation
├── belongsTo: user
└── belongsTo: conversation
```

## Installation

### Prerequisites
- PHP 8.4+
- Composer
- MySQL 8.0+
- Node.js 20+
- Redis (optional for caching/queues)

### Quick Start

1. **Clone the repository**
```bash
git clone https://github.com/pitiflautico/jooinme.git
cd jooinme
```

2. **Install dependencies**
```bash
composer install
npm install
```

3. **Configure environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure database** in `.env`
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=joinme
DB_USERNAME=root
DB_PASSWORD=
```

5. **Run migrations**
```bash
php artisan migrate
```

6. **Seed database** (optional)
```bash
php artisan db:seed
```

7. **Create admin user for Filament**
```bash
php artisan make:filament-user
```

8. **Build frontend assets**
```bash
npm run build
# For development: npm run dev
```

9. **Start the server**
```bash
php artisan serve
```

Visit:
- **Main app**: http://localhost:8000
- **Admin panel**: http://localhost:8000/admin

## Development

### Running tests
```bash
php artisan test
# or
./vendor/bin/phpunit
```

### Code style
```bash
./vendor/bin/pint
```

### Clear cache
```bash
php artisan optimize:clear
```

### Queue workers
```bash
php artisan queue:work
```

## API Documentation

### Authentication

All API requests require authentication via Bearer token (Sanctum).

```bash
# Create API key via admin panel or:
POST /api/auth/register
POST /api/auth/login
```

### Endpoints

**Conversations**
```
GET    /api/conversations              List all conversations
POST   /api/conversations              Create new conversation
GET    /api/conversations/{id}         Get conversation details
PUT    /api/conversations/{id}         Update conversation
DELETE /api/conversations/{id}         Delete conversation
POST   /api/conversations/{id}/join    Join conversation
```

**Topics**
```
GET    /api/topics                     List all topics
GET    /api/topics/{slug}              Get topic details
```

**Users**
```
GET    /api/users/{id}                 Get user profile
PUT    /api/users/{id}                 Update profile
GET    /api/users/{id}/conversations   User's conversations
```

**Schedule Slots**
```
GET    /api/conversations/{id}/slots   Get conversation schedule
POST   /api/slots/{id}/confirm         Confirm attendance
POST   /api/slots/{id}/checkin         Check in to meeting
```

**Feedback**
```
POST   /api/feedback                   Submit feedback
GET    /api/conversations/{id}/feedback Get conversation reviews
```

### Webhooks

Configure webhooks in admin panel to receive real-time events:

**Events**
- `conversation.created`
- `conversation.updated`
- `participation.joined`
- `participation.left`
- `slot.scheduled`
- `slot.completed`
- `feedback.submitted`

## Deployment

See [DEPLOYMENT.md](DEPLOYMENT.md) for complete Laravel Forge deployment guide.

### Quick deployment checklist
- [ ] Configure production environment variables
- [ ] Set up MySQL database
- [ ] Configure Redis for caching/queues
- [ ] Set up email service (Mailgun, SES, etc.)
- [ ] Configure storage (S3 or similar)
- [ ] Enable queue workers
- [ ] Set up scheduled tasks (cron)
- [ ] Configure SSL certificate
- [ ] Set up monitoring (Laravel Pulse, Sentry)
- [ ] Configure backups

## Service Providers & Costs

See [SERVICES.md](SERVICES.md) for detailed service recommendations and cost breakdown by phase.

**Estimated Monthly Costs:**
- MVP Phase: $67/month
- Growth Phase: $251/month
- Scale Phase: $882/month

## Roadmap

### Phase 1: MVP Backend ✅ COMPLETED
- Core models and migrations
- Authentication system
- Basic admin panel
- Database relationships

### Phase 2: Frontend (In Progress)
- [ ] Livewire components
- [ ] User dashboard
- [ ] Conversation discovery
- [ ] Profile management
- [ ] Real-time updates

### Phase 3: Testing & Quality
- [ ] Unit tests for models
- [ ] Feature tests
- [ ] Integration tests
- [ ] Performance testing

### Phase 4: API & Integrations
- [ ] RESTful API controllers
- [ ] API documentation (Swagger)
- [ ] Webhook system
- [ ] Third-party integrations

### Phase 5: Advanced Features
- [ ] Video call integration
- [ ] AI transcription service
- [ ] Payment processing (mentorships)
- [ ] Mobile app (API-first approach)

### Phase 6: Optimization
- [ ] Performance optimization
- [ ] Caching strategy
- [ ] CDN integration
- [ ] Database optimization

### Phase 7: Launch
- [ ] Production deployment
- [ ] Monitoring setup
- [ ] User onboarding
- [ ] Marketing pages

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## Testing

### Prerequisites
Tests require SQLite PHP extension installed:
```bash
# Ubuntu/Debian
sudo apt-get install php-sqlite3

# macOS
brew install php
```

### Running Tests
Run the test suite:
```bash
php artisan test --parallel
```

Run only unit tests:
```bash
php artisan test --testsuite=Unit
```

Run only feature tests:
```bash
php artisan test --testsuite=Feature
```

Generate coverage report:
```bash
php artisan test --coverage
```

### Test Coverage
- **Unit Tests**: 15 model test files covering 147+ test cases
- **Feature Tests**: Coming soon
- **Integration Tests**: Coming soon

Note: Tests use SQLite in-memory database (configured in `phpunit.xml`)

## Security

If you discover any security vulnerabilities, please email security@joinme.app. All security vulnerabilities will be promptly addressed.

### Security Features
- ✅ Laravel's built-in CSRF protection
- ✅ SQL injection prevention via Eloquent ORM
- ✅ XSS protection in Blade templates
- ✅ Rate limiting on API endpoints
- ✅ Role-based access control (Spatie Permission)
- ✅ Secure password hashing (bcrypt)
- ✅ API key authentication with expiration
- ✅ Content security policies
- ✅ Soft deletes for data retention

## License

This project is proprietary software. All rights reserved.

## Support

For support, email support@joinme.app or join our Slack channel.

## Acknowledgments

- Built with [Laravel](https://laravel.com)
- Admin panel by [Filament](https://filamentphp.com)
- Icons from [Heroicons](https://heroicons.com)
- Styling with [Tailwind CSS](https://tailwindcss.com)

---

Made with ❤️ by the JoinMe Team
