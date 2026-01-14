# Vremya Cheloveka - Current Context

## Current State

The project is a **fully functional PHP-based charitable foundation website** with modern glassmorphism design. The site is production-ready with content management capabilities.

### Recently Completed
- ✅ Glassmorphism dark theme implementation (January 2026)
- ✅ Adaptive navigation (top for internal pages, bottom for homepage)
- ✅ Full responsive design (desktop, tablet, mobile)
- ✅ Content structure with projects, news, reports
- ✅ Admin panel for content management
- ✅ Hero section with video background
- ✅ Project filtering and categorization
- ✅ Milestone tracking system

### Current Focus

The website is in **stable production state** with all core features implemented. No active development work is currently in progress.

**Key Pages Available:**
- Homepage (`index.php`) - Hero video, mission, values, activities, featured projects
- About page (`about.php`) - Foundation details, mission, governance structure
- Projects listing (`projects.php`) - Filterable project cards
- Single project (`project.php`) - Detailed project view with milestones
- News listing (`news.php`) - Latest foundation news
- Single news (`news-single.php`) - Individual news articles
- Documents (`documents.php`) - Legal and financial documents
- Contacts (`contacts.php`) - Contact information and form

## Recent Changes

### Design System Update (Jan 2026)
Implemented comprehensive glassmorphism design system documented in `docs/GLASSMORPHISM_DESIGN.md`:
- Dark gradient backgrounds with radial gradients
- Glass-effect cards with backdrop-filter
- Golden accent colors (#fde9a9, #ffb340)
- Adaptive navigation positioning
- Smooth animations and transitions

### Content Structure
All content stored as Markdown files with YAML frontmatter:
- **Projects**: `content/projects/*.md` (3 active projects)
- **News**: `content/news/*.md` (2 articles)
- **Reports**: `content/reports/*.md` (2 financial reports)
- **Partners**: `content/partners/*.json` (2 partners)

## Next Steps

### Immediate Priorities
No urgent development tasks. Site is stable and functional.

### Planned Enhancements (Not Started)
1. **Robokassa Payment Integration** - Payment widgets are placeholders
   - Location: Documented in `docs/ROBOKASSA_INTEGRATION.md`
   - Requires: Merchant credentials from Robokassa
   - Impact: Enable real donations

2. **Admin Panel Enhancement**
   - Currently basic CRUD operations
   - Could add: Image upload optimization, markdown preview

3. **Performance Optimization**
   - Add caching layer (APCu or file-based)
   - Image optimization (WebP conversion)
   - Consider CDN for static assets

4. **Additional Pages** (referenced but not created)
   - `/privacy` - Privacy policy
   - `/terms` - Terms of service
   - `/legal` - Legal information

## Known Issues

### None Critical
No blocking issues or bugs reported. Site functions as designed.

### Future Considerations
- **Package.json confusion**: File references Astro framework but project uses pure PHP
  - Decision needed: Remove Astro references or plan migration
- **No automated tests**: Rely on manual testing
- **Basic error handling**: Could be enhanced with proper logging system

## Technical Environment

**Current Stack:**
- PHP 7.4+ (server: PHP 8.3.27)
- Apache with mod_rewrite
- No database - file-based content
- No JavaScript framework dependencies
- Font Awesome 6.4.0 for icons
- Google Fonts (Montserrat, Inter)

**Hosting:**
- Server path: `/var/www/u0557545/data/www/vremyacheloveka.ru/`
- Domain: `vremyacheloveka.ru`
- Shared hosting environment

## Content Status

### Projects (3 total)
1. **Трезвая Россия** (Trezvaya Rossiya) - Active, Featured
   - Target: 5,000,000 ₽
   - Collected: 1,250,000 ₽ (25%)
   - Beneficiaries: 15,000 people
   - 5 regional centers, 4 milestones (2 completed)

2. **Будущее наших детей** (Future of Our Children) - Active, Featured
   - Support for children in institutions
   - Educational programs and facilities

3. **Ответственное отцовство** (Responsible Fatherhood) - Active
   - Educational seminars
   - Men's support clubs

### News (2 articles)
1. **Итоги 2024 года** - Featured, December 26, 2024
   - Year-end summary: 18,350 beneficiaries across 45 regions
   
2. **Открытие центра в Екатеринбурге** - Center opening announcement

### Design Themes Available
Multiple color palettes documented in `docs/COLOR_PALETTES.md` and `docs/THEME_VARIANTS.md`:
- Current: Light theme (Variant 2) with warm golden cards
- Alternative: Dark theme (Variant 1) available
- 6 Sovietwave palettes for potential theming

## Dependencies & Integrations

### Current
- Font Awesome (CDN)
- Google Fonts (CDN)
- Custom CSS only (no frameworks)

### Planned
- Robokassa payment gateway (not integrated)

### Admin Panel
- Location: `/admin/`
- Authentication: .htaccess + .htpasswd
- Features: CRUD for projects/news, auto-slug generation
- Documentation: `admin/README.md`, `admin/DEPLOYMENT.md`

## Documentation

All documentation located in project root and `/docs`:
- `brief.md` - Architecture overview (memory bank)
- `docs/GLASSMORPHISM_DESIGN.md` - Design system documentation
- `docs/GLASSMORPHISM_QUICKSTART.md` - Quick reference guide
- `docs/ROBOKASSA_INTEGRATION.md` - Payment integration guide
- `docs/COLOR_PALETTES.md` - Available color schemes
- `docs/THEME_VARIANTS.md` - Theme switching instructions
- `admin/README.md` - Admin panel documentation
- `admin/DEPLOYMENT.md` - Deployment instructions

## Development Workflow

### Content Updates
1. Moderator accesses `/admin/`
2. Edits Markdown file through interface
3. Saves → changes immediately visible
4. No build/deploy step required

### Code Changes
1. Edit PHP/CSS files locally
2. Test on development server
3. Upload via SFTP to production
4. Changes take effect immediately

### No Active Development
Currently in maintenance mode - no active feature development or bugfixes in progress.