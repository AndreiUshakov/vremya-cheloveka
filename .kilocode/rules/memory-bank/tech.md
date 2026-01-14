# Vremya Cheloveka - Technical Stack

## Core Technologies

### Backend
- **PHP 8.3.27** - Server-side language
  - Pure PHP implementation (no frameworks)
  - File-based content management
  - Custom routing via `.htaccess`
  - Server: Apache with `mod_rewrite`

### Frontend
- **HTML5** - Semantic markup
- **CSS3** - Custom styling with CSS variables
- **Vanilla JavaScript** - Minimal JS for interactions
- **No frontend framework** - Server-side rendering only

### Content Storage
- **Markdown** - Content files with YAML frontmatter
- **JSON** - Partner data storage
- **File System** - No database required

## Development Stack

### Local Development
- PHP 7.4+ required (8.x recommended)
- Apache with mod_rewrite enabled
- Text editor (VS Code recommended)
- Browser developer tools

### Server Requirements
- PHP 8.3+ with extensions:
  - `mbstring` - Multibyte string handling
  - `fileinfo` - File type detection
  - `json` - JSON parsing
- Apache 2.4+ with modules:
  - `mod_rewrite` - URL rewriting
  - `mod_headers` - Security headers
- File system permissions: 755 for directories, 644 for files

### Development Tools
- SFTP client for deployment (FileZilla, Cyberduck)
- Git for version control (optional)
- Browser DevTools for debugging

## Project Structure

```
vremya-cheloveka/
├── .htaccess              # Apache configuration
├── .gitignore             # Git ignore rules
├── config.php             # Site configuration
├── index.php              # Homepage
├── about.php              # About page
├── projects.php           # Projects listing
├── project.php            # Single project
├── news.php               # News listing
├── news-single.php        # Single news article
├── documents.php          # Documents page
├── contacts.php           # Contact page
├── package.json           # Node config (unused - Astro remnant)
├── admin/                 # Admin panel
│   ├── .htaccess          # Admin access control
│   ├── .htpasswd          # Admin passwords
│   ├── config.php         # Admin configuration
│   ├── index.php          # Admin dashboard
│   ├── dashboard.php      # Statistics
│   ├── list.php           # Content listing
│   ├── edit.php           # Content editor
│   ├── delete.php         # Content deletion
│   ├── styles.css         # Admin styles
│   ├── slug-generator.js  # Auto-slug generation
│   ├── image-upload.js    # Image handling
│   ├── README.md          # Admin documentation
│   └── DEPLOYMENT.md      # Deployment guide
├── content/               # Content files
│   ├── projects/          # Project markdown files
│   ├── news/              # News markdown files
│   ├── reports/           # Report markdown files
│   └── partners/          # Partner JSON files
├── includes/              # PHP components
│   ├── layout.php         # Template system
│   └── MarkdownParser.php # Content parser
├── static/                # Static assets
│   ├── styles.css         # Main stylesheet (Light theme)
│   ├── glass-theme.css    # Glassmorphism theme
│   ├── styles-old.css     # Backup styles
│   ├── projects.js        # Project filtering
│   ├── hero-video.mp4     # Hero video
│   ├── img/               # Images
│   │   ├── bg1.png        # Background image
│   │   ├── bg2.png        # Alternative background
│   │   ├── brand.png      # Brand logo
│   │   ├── logoDark.jpg   # Dark logo
│   │   └── nophoto.svg    # Placeholder image
│   └── documents/         # Downloadable documents
└── docs/                  # Documentation
    ├── GLASSMORPHISM_DESIGN.md
    ├── GLASSMORPHISM_QUICKSTART.md
    ├── ROBOKASSA_INTEGRATION.md
    ├── COLOR_PALETTES.md
    └── THEME_VARIANTS.md
```

## Dependencies

### External Libraries (CDN)
```html
<!-- Font Awesome 6.4.0 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Inter:wght@400;500;600&display=swap">
```

### No Build Dependencies
- No npm packages required for runtime
- No bundler needed
- No compilation step

### Development Dependencies (Optional)
```json
{
  "package.json": "Contains Astro references but not used"
}
```

## Configuration

### Environment Variables
Stored in `config.php`:
```php
define('ROOT_DIR', __DIR__);
define('CONTENT_DIR', ROOT_DIR . '/content');
define('PROJECTS_DIR', CONTENT_DIR . '/projects');
define('NEWS_DIR', CONTENT_DIR . '/news');
define('SITE_NAME', 'Время Человека');
define('SITE_URL', 'https://vremyacheloveka.ru');
```

### Apache Configuration (`.htaccess`)
Key features:
- URL rewriting for clean URLs
- Security headers (XSS, Frame, Content-Type)
- File access restrictions
- GZIP compression
- Browser caching rules

### Admin Configuration (`admin/.htaccess`)
```apache
AuthType Basic
AuthName "Admin Area"
AuthUserFile /path/to/.htpasswd
Require valid-user
```

## Technical Constraints

### Hosting Environment
- **Shared hosting** - Limited resources
- **Apache-only** - No Nginx support
- **No shell access** - SFTP deployment only
- **PHP limitations**:
  - No composer autoloader
  - No package manager
  - File-based sessions

### Browser Support
- **Modern browsers** (Chrome 76+, Firefox 103+, Safari 9+, Edge 79+)
- **Fallbacks** for `backdrop-filter` (older browsers)
- **Progressive enhancement** approach

### Performance Limits
- **No caching** currently implemented
- **File I/O** on every request
- **Synchronous rendering** (no async operations)

## Development Setup

### Initial Setup
```bash
# 1. Clone repository
git clone <repository-url>
cd vremya-cheloveka

# 2. Configure local server
# Point Apache DocumentRoot to project directory
# Enable mod_rewrite

# 3. Set permissions
chmod 755 content/
chmod 644 content/projects/*.md
chmod 644 content/news/*.md

# 4. Create admin password
cd admin/
htpasswd -c .htpasswd admin

# 5. Test locally
# Visit http://localhost/vremya-cheloveka/
```

### Development Workflow
```bash
# 1. Make changes to PHP/CSS files
# 2. Refresh browser (no build step)
# 3. Test admin panel at /admin/
# 4. Deploy via SFTP to production
```

## Deployment Process

### Production Deployment
```bash
# 1. Upload files via SFTP to:
/var/www/u0557545/data/www/vremyacheloveka.ru/

# 2. Verify file permissions:
chmod 755 content/
chmod 644 content/**/*.md

# 3. Test admin access:
https://vremyacheloveka.ru/admin/

# 4. Verify clean URLs work
# Visit https://vremyacheloveka.ru/projects/
```

### File Upload Checklist
- ✅ All PHP files
- ✅ All CSS files
- ✅ Content directory with markdown files
- ✅ Static assets (images, videos)
- ✅ Admin panel files
- ✅ `.htaccess` files
- ⚠️ Do NOT upload: `.git/`, `node_modules/`, `.env`

## Tools & Patterns

### Code Patterns

#### Error Handling
```php
try {
    // Main logic
} catch (Exception $e) {
    logError('Context', ['error' => $e->getMessage()]);
    showErrorPage('Error message', ['details']);
}
```

#### Output Buffering
```php
startContent();
// Page content
endContent(['title' => 'Page Title']);
```

#### Safe Output
```php
<?= e($userInput) ?>  // Always escape output
```

### Utility Functions
```php
// config.php helpers:
e($string)                          // HTML escape
formatDate($date, $format)          // Date formatting
formatAmount($amount)               // Number formatting with spaces
url($path)                          // Generate full URL
createSlug($text)                   // Generate URL-friendly slug
isActive($path)                     // Check active navigation
getCollectionPercentage($c, $t)     // Calculate percentage
logError($message, $context)        // Error logging
showErrorPage($message, $details)   // Custom error page
```

## Performance Optimization

### Current Status
- ❌ No caching implemented
- ❌ No CDN usage
- ❌ No image optimization
- ✅ GZIP compression enabled
- ✅ Browser caching headers set

### Recommended Optimizations
1. **Add APCu caching** for parsed Markdown
2. **Implement HTTP caching** headers
3. **Optimize images** (WebP format)
4. **Add lazy loading** for images
5. **Minify CSS** in production
6. **Consider CDN** for static assets

### Caching Strategy (Planned)
```php
// Example APCu implementation
$cacheKey = 'projects_all';
$cacheTTL = 3600; // 1 hour

if (apcu_exists($cacheKey)) {
    $projects = apcu_fetch($cacheKey);
} else {
    $projects = MarkdownParser::getAllFromDirectory(PROJECTS_DIR);
    apcu_store($cacheKey, $projects, $cacheTTL);
}
```

## Security Measures

### Current Implementation
- ✅ Input sanitization via `e()` function
- ✅ `.htaccess` file protection
- ✅ Admin password protection
- ✅ Security headers (XSS, Frame, Content-Type)
- ✅ Directory browsing disabled

### Security Headers (`.htaccess`)
```apache
Header set X-XSS-Protection "1; mode=block"
Header set X-Frame-Options "SAMEORIGIN"
Header set X-Content-Type-Options "nosniff"
```

## Testing Approach

### Manual Testing
- Browser testing (Chrome, Firefox, Safari, Edge)
- Mobile device testing (iOS, Android)
- Admin panel functionality
- Form submissions
- Content updates

### No Automated Tests
Currently no automated testing framework. All testing done manually.

## Future Technical Improvements

### Short Term
1. Add APCu caching
2. Implement error logging
3. Add form validation
4. Optimize images

### Medium Term
1. Add automated tests
2. Implement CI/CD pipeline
3. Add monitoring/analytics
4. Database migration consideration

### Long Term
1. API development
2. Mobile app support
3. Multi-language support
4. Advanced caching strategies