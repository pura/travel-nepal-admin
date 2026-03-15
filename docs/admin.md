# Admin (EasyAdmin)

## Making the admin look correct

If the admin pages have no or broken CSS:

1. **Install or refresh bundle assets** (EasyAdmin’s own CSS/JS):

   ```bash
   php bin/console assets:install --symlink
   ```

   With Docker:

   ```bash
   docker compose exec php php bin/console assets:install --symlink
   ```

2. **Custom theme** – The project adds `public/css/admin.css` for a consistent “Travel Nepal” look (sidebar, cards, tables, buttons, forms). It is loaded automatically by the dashboard.

## Menu

The dashboard sidebar includes:

- **Dashboard** – Home
- **Content** – Destinations, Hotels, Guides, Transport
- **Itineraries** – Itinerary templates
- **Contacts** – Representatives

Itinerary template days are managed when editing an itinerary template (embedded collection).
