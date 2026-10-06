# Vantage Luxe Realty — Premium Property Platform Upgrade

This package is a redesign and functional upgrade of the supplied CodeIgniter 4 project. It keeps the Vantage Luxe Realty gold/black brand direction and existing logo while replacing the public property experience and modernizing the admin workflow.

## What changed

- New premium, mobile-first public interface using the Vantage gold/black brand palette.
- Fast property discovery with AJAX filtering and pagination. Search URLs remain normal GET URLs, so listings still work without JavaScript and remain crawlable.
- Redesigned property detail pages with responsive image galleries, primary-image management, professional YouTube embeds, amenities, virtual-tour links, related listings and structured enquiry forms.
- New property request workflow:
  1. customer submits a request on a specific property;
  2. the request is validated and saved to `property_requests`;
  3. the server prepares a WhatsApp message containing the request reference, customer details, request type, property details, price and canonical property URL;
  4. the browser continues to WhatsApp using the configured business number.
- New admin request inbox with statuses: New, Contacted, Scheduled and Closed.
- Redesigned property admin with safer image uploads, image previews, primary-image controls, multiple price options, amenities, YouTube URL, virtual-tour URL and SEO fields.
- New admin dashboard metrics for active, pending, sold and incoming requests.
- SEO improvements: canonical URLs, Open Graph/Twitter metadata, JSON-LD, dynamic sitemap, dynamic robots file, semantic headings, clean property slugs and crawlable server-rendered content.
- Performance improvements: local frontend CSS/JS, removal of the old frontend animation/plugin stack, lazy-loaded gallery/card images, no random property queries on the homepage, and indexed search fields in the upgrade SQL.
- CSRF protection is enabled for POST requests.

## Required database upgrade

**Back up your production database first.**

For cPanel/phpMyAdmin, import:

`database/vantage_premium_upgrade.sql`

This creates the new request table, ensures the supporting property media/pricing/amenity tables exist, adds the optional `view_count` field, and adds useful indexes.

If your existing database already has the supporting tables and you prefer CodeIgniter migrations, you may run:

```bash
php spark migrate
```

The included CI migration intentionally focuses on `property_requests` and `view_count`; the SQL file is the more complete deployment path for the supplied project.

## Important configuration

Edit `app/Config/Site.php` and confirm:

- `contactEmail`
- `contactPhone`
- `whatsappNumber` — digits only, international format, e.g. `2348033390219`
- social URLs
- address

The WhatsApp workflow uses `whatsappNumber`. Requests are saved to the database before a WhatsApp URL is returned.

Also make sure `.env` has the correct production `app.baseURL` and database credentials for the deployment server.

## Web server / PHP requirements

The supplied project requires PHP 8.2+ from `composer.json`. The host should enable the normal CodeIgniter extensions, particularly `mbstring`, `intl`, `mysqli`, `json` and `openssl`.

The writable directories must remain writable, and the web server must be able to create/write:

`public/uploads/properties/`

## SEO deployment checklist

1. Set the final production `app.baseURL` using HTTPS.
2. Confirm `https://your-domain/sitemap.xml` loads.
3. Confirm `https://your-domain/robots.txt` loads and points to the sitemap.
4. Add the final domain to Google Search Console and submit `/sitemap.xml`.
5. For important listings, add meaningful `meta_title` and `meta_description` in admin; otherwise the site generates sensible fallbacks.
6. Use descriptive property titles, locations and image selections. Search visibility cannot be guaranteed, but the code now exposes the technical signals needed for indexing.

## Functional verification after deployment

- Sign in to `/login`.
- Create or edit a property and upload multiple JPG/PNG/WebP images.
- Set a primary image.
- Add a YouTube URL and confirm the video appears on the public property page.
- Browse `/properties` and test filters/pagination without a full page refresh.
- Submit a property request from a property page.
- Confirm the row appears under **Admin → Property Requests**.
- Confirm WhatsApp opens with the request and property URL pre-filled.
- Change the request status in admin.

## Notes about the supplied project

The package preserves the existing CodeIgniter structure, login system, existing logo assets, property records and legacy property price columns. The upgraded code also continues to read legacy price values when a property does not yet have rows in `property_prices`.
