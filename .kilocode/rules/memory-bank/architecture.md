# Vremya Cheloveka - Architecture

## System Architecture

### High-Level Overview

```
┌─────────────────────────────────────────────────────────────┐
│                         User Browser                         │
└───────────────────────────┬─────────────────────────────────┘
                            │
                            ↓
┌─────────────────────────────────────────────────────────────┐
│                    Apache Web Server                         │
│  ┌──────────────────────────────────────────────────────┐   │
│  │                    .htaccess                          │   │
│  │  - URL Rewriting (ЧПУ - Clean URLs)                 │   │
│  │  - Security headers                                   │   │
│  │  - Access control                                     │   │
│  └──────────────────────────────────────────────────────┘   │
└───────────────────────────┬─────────────────────────────────┘
                            │
                            ↓
┌─────────────────────────────────────────────────────────────┐
│                      PHP Layer (8.3+)                        │
│  ┌─────────────────┐  ┌──────────────┐  ┌───────────────┐  │
│  │   config.php    │  │  layout.php  │  │ Page Files    │  │
│  │  - Constants    │  │  - Template  │  │ - index.php   │  │
│  │  - Helpers      │  │  - Header    │  │ - projects.php│  │
│  │                 │  │  - Footer    │  │ - project.php │  │
│  └─────────────────┘  └──────────────┘  └───────────────┘  │
│                                                               │
│  ┌──────────────────────────────────────────────────────┐   │
│  │            MarkdownParser.php                         │   │
│  │  - YAML frontmatter parsing                          │   │
│  │  - Markdown to HTML conversion                       │   │
│  │  - File system operations                            │   │
│  │  - Sorting, filtering, featured content              │   │
│  └──────────────────────────────────────────────────────┘   │
└───────────────────────────┬─────────────────────────────────┘
                            │
                            ↓
┌─────────────────────────────────────────────────────────────┐
│                  File System (Content)                       │
│  content/                                                    │
│  ├── projects/          (Markdown files)                    │
│  ├── news/              (Markdown files)                    │
│  ├── reports/           (Markdown files)                    │
│  └── partners/          (JSON files)                        │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                     Admin Panel (/admin)                     │
│  - Protected by .htaccess + .htpasswd                       │
│  - CRUD operations for content                              │
│  - Direct file manipulation                                  │
└─────────────────────────────────────────────────────────────┘
```

### Key Architectural Decisions

1. **No Database**: Content stored as files (Markdown + JSON)
   - Pros: Portable, version-controllable, no DB maintenance
   - Cons: Not suitable for high-concurrency writes

2. **Server-Side Rendering**: Pure PHP, no JavaScript framework
   - Pros: Fast initial load, SEO-friendly, works without JS
   - Cons: Full page reloads, no SPA features

3. **File-Based CMS**: Direct file editing via admin panel
   - Pros: Simple, transparent, easy backup
   - Cons: No revision history, concurrent edit conflicts

4. **Clean URL Routing**: Apache mod_rewrite
   - Pros: SEO-friendly, user-friendly URLs
   - Cons: Requires Apache, .htaccess support

## Source Code Paths

### Core System Files

#### Configuration Layer
- **`/config.php`** (203 lines)
  - Site-wide constants and settings
  - Helper functions (e(), formatDate(), formatAmount(), etc.)
  - Directory path definitions
  - Category and status enumerations

#### Template System
- **`/includes/layout.php`** (166 lines)
  - `renderLayout()` - Main layout wrapper
  - `startContent()` - Begin content buffering
  - `endContent()` - Render complete page with header/footer
  - Adaptive navigation (top vs bottom positioning)
  - Meta tags (OG, Twitter cards)

#### Content Parser
- **`/includes/MarkdownParser.php`** (294 lines)
  - `parse($filePath)` - Parse single Markdown file
  - `parseYaml($yaml)` - Custom YAML parser
  - `markdownToHtml($markdown)` - Convert Markdown to HTML
  - `getAllFromDirectory($dir)` - Load all files from directory
  - `getBySlug($dir, $slug)` - Find file by slug
  - `sort($items, $field, $order)` - Sort content
  - `filter($items, $filters)` - Filter by criteria
  - `getFeatured($items, $limit)` - Get featured items

### Page Files

#### Public Pages
- **`/index.php`** (594 lines) - Homepage with Hero video, featured projects, news
- **`/about.php`** (402 lines) - About foundation, mission, governance
- **`/projects.php`** (127 lines) - Project listing with filters
- **`/project.php`** (230 lines) - Single project detail page
- **`/news.php`** (111 lines) - News listing
- **`/news-single.php`** - Individual news article
- **`/documents.php`** - Legal documents page
- **`/contacts.php`** - Contact information

#### URL Routing (`.htaccess`)
```apache
RewriteRule ^projects/?$ projects.php [L]
RewriteRule ^projects/([^/]+)/?$ project.php?slug=$1 [L]
RewriteRule ^news/?$ news.php [L]
RewriteRule ^news/([^/]+)/?$ news-single.php?slug=$1 [L]
```

### Admin Panel

#### Admin Files (`/admin/`)
- **`index.php`** - Admin dashboard
- **`dashboard.php`** - Statistics overview
- **`list.php`** - List projects/news
- **`edit.php`** - Create/edit content
- **`delete.php`** - Delete content
- **`config.php`** - Admin configuration
- **`.htaccess`** - Access control
- **`.htpasswd`** - Password file (created manually)

### Static Assets

#### Stylesheets (`/static/`)
- **`styles.css`** (747 lines) - Main stylesheet (Light theme, Variant 2)
  - Hero video section
  - Project cards with golden accents
  - Responsive grid layouts
  - Form styling
  
- **`glass-theme.css`** (1314 lines) - Glassmorphism theme
  - Dark backgrounds with gradients
  - Glass effect cards (backdrop-filter)
  - Golden accent colors
  - Adaptive navigation
  - Animation keyframes

- **`styles-old.css`** - Backup of previous styles

#### JavaScript
- **`projects.js`** - Project filtering and interactions
- **`slug-generator.js`** (admin) - Auto-generate slugs from titles
- **`image-upload.js`** (admin) - Image upload handling

#### Media
- **`hero-video.mp4`** - Homepage hero background video
- **`/static/img/`** - Project images, logos, backgrounds

## Technical Decisions

### Content Storage Format

**Markdown with YAML Frontmatter:**
```markdown
---
title: "Project Title"
slug: "project-slug"
category: "social"
status: "active"
featured: true
publishedAt: 2024-01-15
---

## Content in Markdown

Body text with **formatting**.
```

**Why this format:**
- Human-readable and editable
- Git-friendly (easy diffs)
- No lock-in (portable to any system)
- Simple parsing with custom MarkdownParser

### Design Patterns

#### 1. Template Pattern (Layout System)
```php
startContent();
// Buffered content goes here
endContent(['title' => 'Page Title']);
```

**Benefits:**
- Consistent page structure
- DRY (Don't Repeat Yourself)
- Easy to maintain header/footer

#### 2. Static Class Pattern (MarkdownParser)
```php
$projects = MarkdownParser::getAllFromDirectory(PROJECTS_DIR);
$project = MarkdownParser::getBySlug(PROJECTS_DIR, $slug);
```

**Benefits:**
- No state needed (stateless operations)
- Simple API
- Easy to test

#### 3. Configuration Pattern (Constants)
```php
define('SITE_NAME', 'Время Человека');
define('PROJECTS_DIR', ROOT_DIR . '/content/projects');
```

**Benefits:**
- Single source of truth
- Easy to change settings
- No magic strings in code

### Security Measures

#### Input Sanitization
```php
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

// Usage
<?= e($user_input) ?>
```

#### .htaccess Protection
```apache
# Block access to sensitive files
<FilesMatch "\.(htaccess|htpasswd|env|md|json)$">
    Require all denied
</FilesMatch>

# Block directory access
<DirectoryMatch "^/.*(includes|content)">
    Require all denied
</DirectoryMatch>
```

#### Admin Authentication
- HTTP Basic Auth via `.htaccess`
- `.htpasswd` file with hashed passwords
- No direct file access without authentication

### Performance Considerations

#### Current Implementation
- **No caching**: Files read on every request
- **No CDN**: Static assets served from origin
- **No optimization**: Images not compressed/converted

#### For High Traffic (Future)
```php
// APCu caching example
if (apcu_exists('projects')) {
    $projects = apcu_fetch('projects');
} else {
    $projects = MarkdownParser::getAllFromDirectory(PROJECTS_DIR);
    apcu_store('projects', $projects, 3600); // 1 hour
}
```

## Component Relationships

### Data Flow: Viewing a Project

```
1. User visits: /projects/trezvaya-rossiya
   ↓
2. Apache rewrites to: project.php?slug=trezvaya-rossiya
   ↓
3. project.php loads:
   - config.php (constants, helpers)
   - layout.php (template functions)
   ↓
4. MarkdownParser::getBySlug(PROJECTS_DIR, 'trezvaya-rossiya')
   ↓
5. Reads: content/projects/trezvaya-rossiya.md
   - Parses YAML frontmatter
   - Converts Markdown to HTML
   ↓
6. Returns array with metadata + content
   ↓
7. project.php renders using layout.php
   ↓
8. HTML sent to browser
```

### Data Flow: Content Updates

```
1. Admin logs into /admin/
   ↓
2. Selects project to edit
   ↓
3. edit.php displays form with current content
   ↓
4. Admin modifies and saves
   ↓
5. edit.php writes to: content/projects/trezvaya-rossiya.md
   ↓
6. Next visitor sees updated content immediately
```

## Critical Implementation Paths

### Error Handling Pattern

Every page uses this pattern:
```php
try {
    require_once __DIR__ . '/config.php';
    require_once INCLUDES_DIR . '/layout.php';
    
    // Page logic here
    
} catch (Exception $e) {
    logError('Context', ['error' => $e->getMessage()]);
    showErrorPage('Error message', ['details']);
}
```

### Markdown Parsing Pipeline

```
1. Read file content
   ↓
2. Split into frontmatter and body
   ↓
3. Parse YAML frontmatter
   - Handle scalars, arrays, objects
   - Type conversion (dates, numbers, booleans)
   ↓
4. Convert Markdown body to HTML
   - Headings (##, ###)
   - Bold (**text**), Italic (*text*)
   - Links ([text](url))
   - Lists (-, *, numbered)
   ↓
5. Return array: ['metadata' => [...], 'content' => '...']
```

### Navigation Highlighting

```php
function isActive($path) {
    $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    return strpos($currentPath, $path) === 0;
}

// Usage in layout
<a href="/projects" class="<?= isActive('/projects') ? 'active' : '' ?>">
    Проекты
</a>
```

## Future Architecture Considerations

### Potential Improvements

1. **Caching Layer**
   - File-based cache for parsed Markdown
   - APCu for frequently accessed data
   - HTTP cache headers

2. **Database Migration**
   - For high-concurrency scenarios
   - Better search capabilities
   - Revision history tracking

3. **API Layer**
   - RESTful API for mobile apps
   - JSON responses
   - Authentication tokens

4. **Build Process**
   - Asset compilation (SCSS → CSS)
   - Image optimization pipeline
   - JavaScript bundling

### Scalability Path

```
Current:    Apache + PHP + File System
            ↓
Step 1:     Add APCu caching
            ↓
Step 2:     Add CDN for static assets
            ↓
Step 3:     Migrate to SQLite/PostgreSQL
            ↓
Step 4:     Add Redis for sessions/cache
            ↓
Step 5:     Containerize with Docker
```

## Integration Points

### Current Integrations
- Font Awesome (CDN)
- Google Fonts (CDN)

### Planned Integrations
- **Robokassa** - Payment gateway
  - Merchant API
  - Webhook callbacks
  - Signature verification
  - Documentation: `docs/ROBOKASSA_INTEGRATION.md`

### Potential Integrations
- Email service (SendGrid, Mailgun)
- Analytics (Google Analytics, Yandex.Metrica)
- Search (Algolia, Meilisearch)
- Forms (reCAPTCHA)