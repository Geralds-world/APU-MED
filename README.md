APU — Static Dark-Themed Medical & Emergency Supplies Site

Overview
- Minimal, responsive, dark-themed e-commerce prototype for APU.
- Built with HTML5, CSS3 and vanilla JavaScript (no frameworks).
- Product data is defined in `js/app.js` and prices are in ZAR.

Files
- `index.html` — Main site structure and sections.
- `css/styles.css` — Dark theme, responsive layout, animations.
- `js/app.js` — Product rendering, cart logic, checkout & notifications.

Development Notes
- Replace the placeholder logo text in the header with your SVG or image.
- Product images are placeholders; update `PRODUCTS` in `js/app.js` to include `image` fields and adjust the card template.
- Yoco integration: the site now supports a local Node/Express helper server that creates checkout sessions securely server-side.
  - Start the server with the `YOCO_SECRET_KEY` set as an environment variable (see `server/.env.example`). The server will call Yoco's API using the secret key and return a `checkoutUrl` to the client.
  - If you prefer not to run a server, the server will fall back to building a simple `https://pay.yoco.com/checkout?amount=...&reference=...` URL.
  - **Important**: Do NOT commit your secret key to the repo. Use environment variables and keep secrets out of source control.
Order Flow
- Minimum order value: R150 enforced client-side (also validate server-side when adding backend).
- Checkout opens a Yoco link, then displays an on-site order confirmation modal with quick actions:
  - Open WhatsApp to message APU (+27 72 335 6685)
  - Open default email client with order summary
  - Copy order summary to clipboard

Accessibility & SEO
- Semantic HTML, focus states, and keyboard accessibility for cart (Esc to close).
- Basic meta description included; add structured data and analytics as needed.

Next Steps
- Integrate real Yoco checkout configuration.
- Add product images and improve product detail views.
- Add order persistence/server-side processing and email sending.
- Add analytics, SEO improvements, and automated accessibility testing.

Contact
- Phone / WhatsApp: 072 335 6685

License
- MIT — adapt and extend as needed.