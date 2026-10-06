# Vantage Luxe Realty — Admin Catalog & Pricing Update v2.1

This update makes **Property Types** and **Property Purposes** database-driven, adds purpose-aware pricing, automatic discount-percentage calculation, and improves the Add/Edit Property experience on mobile.

## What changed

- `property_types` master table with Admin CRUD, active/inactive state and sort order.
- `property_purposes` master table with Admin CRUD, active/inactive state and sort order.
- Existing property-type text values are automatically imported into `property_types` by the SQL patch.
- Existing `sale`, `rent` and `shortlet` purposes are seeded as **For Sale**, **For Rent**, and **Shortlet (Daily)**.
- `properties.purpose` is converted from ENUM to VARCHAR so future purposes are truly dynamic.
- `properties.purpose_id` and `properties.property_type_id` are added and backfilled.
- Add/Edit Property now uses database dropdowns for Purpose and Property Type.
- Admin Property Inventory now filters using those database records.
- Every price option can optionally be associated with a Purpose.
- `property_prices.purpose_id` is added.
- `property_prices.discount_percentage` is added.
- Discount percentage updates instantly while the admin types a discount amount, and is recalculated on the server before saving.
- Discount values greater than or equal to the normal price are rejected.
- Pricing cards and the complete property editor have been reworked for phone/tablet layouts.
- Public listing filters now read active Purposes and Property Types from the database as well, so new admin values can be used outside the admin area.

## Recommended installation on your current upgraded project

### 1. Back up first

Back up both the project directory and the MySQL database.

### 2. Apply the database patch

In phpMyAdmin, select the Vantage database and import:

`database/vantage_admin_catalog_upgrade.sql`

The same SQL file is also supplied separately with the download.

This patch is written for the MariaDB 10.4+ environment already used by the project. It imports existing property types and backfills the new IDs.

### 3. Replace the updated application files

If using the **full project ZIP**, replace the project with the supplied package while preserving your real `.env`.

If using the **patch-only ZIP**, copy its contents over the matching paths in your existing project.

Do not overwrite your production `.env` with example credentials.

### 4. Clear stale files/browser cache

If CodeIgniter cache is enabled, clear `writable/cache/` without deleting its `.gitkeep`/index protection files. Then hard-refresh the browser so `vantage-admin.css?v=2.1.0` and `vantage-admin.js?v=2.1.0` are loaded.

## New admin pages

- `/admin/property-types`
- `/admin/purposes`
- `/admin/properties/create`
- `/admin/properties`

The sidebar now contains a **Listing setup** section for Property Types, Purposes and Amenities.

## Database verification

After importing the patch, these should work in phpMyAdmin:

```sql
SELECT * FROM property_types ORDER BY sort_order, name;
SELECT * FROM property_purposes ORDER BY sort_order, name;
DESCRIBE properties;
DESCRIBE property_prices;
```

You should see:

- `properties.purpose_id`
- `properties.property_type_id`
- `property_prices.purpose_id`
- `property_prices.discount_percentage`

## Functional test

1. Open **Admin → Property Types** and confirm your existing types are present.
2. Add a new type, e.g. `Penthouse`.
3. Open **Admin → Purposes** and confirm For Sale, For Rent and Shortlet (Daily) are available.
4. Add or edit a property and confirm Purpose/Property Type are dropdowns.
5. Add two price options.
6. Enter a normal price of `25,000,000` and a discount price of `20,000,000`; the form should show `20% OFF` immediately.
7. Assign an optional purpose to one price row and leave the other as General / any purpose.
8. Save and edit the property again; all values should persist.
9. Check the form on a mobile-size browser viewport.

## Important data rule

Do not delete a Property Type or Purpose that is already assigned to records. The admin protects against this and asks you to **deactivate** it instead. This prevents broken historical listings.

## Database migration alternative

A CodeIgniter migration is included:

`app/Database/Migrations/2026-10-05-000002_PropertyCatalogAndPricing.php`

You may run `php spark migrate` instead of importing the SQL patch if your CLI PHP has the required CodeIgniter extensions and is connected to the correct database. For local XAMPP/phpMyAdmin, importing the SQL patch is usually simpler.
