# Vremya Cheloveka - Product Overview

## What This Project Is

**Vremya Cheloveka** (Время Человека - "Human Time") is a charitable foundation website that serves as the digital presence for a Russian non-profit organization focused on people-saving initiatives and education. The website enables the foundation to:

1. **Showcase charitable projects** and their impact
2. **Accept donations** from supporters
3. **Share news** and updates about foundation activities
4. **Provide transparency** through reports and documentation
5. **Recruit volunteers** and partners

## Problems It Solves

### For the Foundation
- **Content Management Without Technical Skills**: Non-technical moderators can update projects, news, and reports through a PHP admin panel without touching code or requiring Git access
- **Immediate Publishing**: Changes are visible instantly without build/deploy cycles
- **Cost-Effective Hosting**: Pure PHP solution runs on affordable shared hosting
- **Data Portability**: All content stored in human-readable Markdown files

### For Donors
- **Transparency**: Clear project descriptions with fundraising progress, beneficiary counts, and regional impact
- **Trust Factors**: Documented achievements, financial reports, and partnership information
- **Easy Donation**: Integrated payment widget (Robokassa) for convenient contributions
- **Mobile Accessibility**: Responsive design works on all devices

### For Volunteers & Partners
- **Project Discovery**: Browse active projects by category and status
- **Contact Options**: Multiple ways to reach the foundation
- **Documentation Access**: View foundation charter, reports, and legal documents

## How It Should Work

### Content Flow
```
Moderator edits content → Saves to Markdown file → PHP reads file → Displays on website
                                                          ↓
                                              NO BUILD STEP REQUIRED
```

### User Journey - Donor
1. Visitor lands on homepage with hero video
2. Reads about foundation mission and values
3. Browses featured projects with progress indicators
4. Clicks project for detailed information
5. Makes donation via integrated payment widget
6. Receives confirmation and updates

### User Journey - Volunteer
1. Discovers foundation through search or referral
2. Reads about foundation activities and impact
3. Views specific projects needing support
4. Contacts foundation through contact form
5. Engages with foundation programs

### Admin Workflow
1. Moderator logs into `/admin/` with credentials
2. Creates/edits project or news article
3. System auto-generates URL slug from title
4. Uploads images via file picker
5. Saves content to Markdown file
6. Content immediately visible on website

## User Experience Goals

### Visual Experience
- **Modern Glassmorphism Design**: Dark theme with translucent glass-like cards, golden accents, and smooth animations
- **Professional yet Warm**: Serious charitable work presented in an approachable way
- **Video-Driven Hero**: Engaging background video immediately communicates foundation's mission
- **Clean Typography**: Montserrat headings + Inter body text for readability

### Navigation Experience
- **Adaptive Navigation Bar**: 
  - Homepage: Bottom-positioned glassmorphic menu
  - Internal pages: Top-positioned menu
  - Active page highlighting
- **Clean URLs**: `/projects/trezvaya-rossiya` instead of `?id=123`
- **Breadcrumbs**: Clear navigation path on internal pages
- **Filter System**: Projects filterable by category and status

### Content Experience
- **Rich Markdown**: Formatted text with headings, lists, links, bold/italic
- **Visual Hierarchy**: Clear sections with icons and progress indicators
- **Responsive Images**: Proper sizing and lazy loading
- **Empty States**: Helpful messages when no content available

### Performance Goals
- **Fast Initial Load**: < 3 seconds on 3G connection
- **Smooth Animations**: 60fps for all transitions
- **Efficient Caching**: Browser caching for static assets
- **Optimized Images**: WebP format with fallbacks

### Accessibility Goals
- **Keyboard Navigation**: All interactive elements accessible via keyboard
- **Color Contrast**: WCAG AA compliant text contrast (4.5:1 minimum)
- **Screen Reader Support**: Semantic HTML with ARIA labels
- **Focus Indicators**: Clear visual focus states

### Mobile Experience
- **Touch-Friendly**: Minimum 44x44px touch targets
- **Responsive Breakpoints**:
  - Desktop: 1024px+
  - Tablet: 768px - 1024px
  - Mobile: < 768px
  - Small: < 480px
- **Adaptive Components**: Navigation collapses to mobile menu
- **Swipe Gestures**: Natural mobile interactions

## Core Features

### 1. Dynamic Content Management
- Markdown-based content with YAML frontmatter
- Custom MarkdownParser class for processing
- Support for projects, news, reports, partners
- Featured content highlighting
- Category and status filtering

### 2. Project Showcase
- Visual cards with images, progress bars, statistics
- Fundraising tracking (target vs. collected amounts)
- Regional impact display
- Milestone tracking with completion status
- Related news integration

### 3. News & Updates
- Featured news highlighting
- Date-based sorting
- Project linkage capability
- Featured image support
- Excerpt generation

### 4. Payment Integration
- Robokassa payment widget placeholders
- Per-project donation tracking
- General fund donations
- Secure signature generation (planned)

### 5. Admin Panel
- Password-protected access (.htaccess)
- CRUD operations for all content types
- Auto slug generation
- Image upload capability
- Markdown editor with preview

### 6. SEO & Social Sharing
- Open Graph meta tags
- Twitter Card support
- Semantic HTML structure
- Clean, keyword-rich URLs
- Sitemap generation capability

## Design Philosophy

### Visual Language
- **Dark & Premium**: Deep backgrounds create sophistication
- **Glass & Light**: Translucent elements suggest transparency
- **Gold Accents**: Warmth and value without ostentation
- **Generous Spacing**: Room to breathe, focus on content

### Content Principles
- **Human-Centered**: People first, numbers second
- **Story-Driven**: Every project tells a transformation story
- **Evidence-Based**: Statistics support narrative claims
- **Action-Oriented**: Clear calls to support/volunteer/learn

### Technical Principles
- **No Black Boxes**: Moderators understand the system
- **Fail Gracefully**: Errors don't break entire site
- **Data-First**: Content structure defines possibilities
- **Progressive Enhancement**: Works without JavaScript

## Success Metrics

### For Foundation
- Increased donation conversion rate
- More volunteer applications
- Higher content update frequency
- Reduced technical support requests

### For Users
- Lower bounce rate on landing
- Increased time on project pages
- Higher donation completion rate
- More social shares

### Technical
- Page load time < 3s
- 99.9% uptime
- Zero critical security issues
- Accessible to 95%+ of users