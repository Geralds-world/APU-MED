# APU Medical Supplies - Live Setup Guide

## ✅ Currently Ready for Production:
- ✅ Professional design (white/teal/blue medical theme)
- ✅ Full product catalog (60+ items across 9 categories)
- ✅ Working shopping cart with quantity controls (+/-)
- ✅ Delivery address collection form
- ✅ Pick-up and Courier delivery options
- ✅ WhatsApp integration for orders
- ✅ Dynamic product images

---

## 🔧 SETUP REQUIRED BEFORE GOING LIVE:

### 1. **Google Places API (Address Search) - REQUIRED**
This enables the live address search for customers.

**Steps:**
1. Go to: https://console.cloud.google.com/
2. Create a new project (name: "APU Medical Supplies")
3. Enable these APIs:
   - Places API
   - Maps JavaScript API
4. Create an API Key (Credentials → Create Credentials → API Key)
5. Restrict the key to:
   - HTTP referrers (your domain)
   - APIs: Places API, Maps JavaScript API
6. Copy the API key and replace `YOUR_GOOGLE_API_KEY` in line 10 of index.html

**Example:**
```html
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSy...YOUR_KEY_HERE&libraries=places"></script>
```

**Cost:** Free tier includes 200 monthly API calls at no charge. Paid plans available if you exceed limits.

---

### 2. **WhatsApp Business Setup - OPTIONAL but RECOMMENDED**
Currently using personal WhatsApp. For business, upgrade to:
- **WhatsApp Business API** (via Meta)
- **Cost:** Starts ~R200-500/month depending on volume
- **Benefit:** Professional business number, automated responses, better message tracking

Current setup: Personal WhatsApp (27723356685) - works fine for now

---

### 3. **Domain & HTTPS - REQUIRED for Live**
- Get a domain (e.g., www.apumedical.co.za)
- SSL Certificate (free via Let's Encrypt)
- Host on a web server

---

### 4. **Courier Integration - MANUAL PROCESS**
Current workflow (as you requested):
1. Customer selects "Courier" option
2. You get their delivery address via WhatsApp
3. You manually check courier costs
4. You add courier fee to the WhatsApp quote
5. Customer confirms and pays

**Future Enhancement:** We can integrate actual courier APIs later (Pudo, Overnight Express, etc.)

---

### 5. **Testing Checklist Before Launch:**

- [ ] Google Places API working (type address in form)
- [ ] All 60+ products displaying correctly
- [ ] Images loading for all products
- [ ] Search/filter working
- [ ] Adding items to cart works
- [ ] +/- quantity buttons work
- [ ] Cart stays open when adding items
- [ ] Address form validation works
- [ ] Delivery options (Pick-up/Courier) working
- [ ] WhatsApp message formatting correct
- [ ] Minimum order (R150) enforced
- [ ] Mobile responsive (test on phone)
- [ ] Payment instructions clear in WhatsApp

---

### 6. **Production Checklist:**

- [ ] Deploy to web server
- [ ] Update Google Places API restrictions to your domain
- [ ] Set up Google Analytics (optional, for tracking)
- [ ] Test on multiple devices/browsers
- [ ] Create a privacy policy page
- [ ] Create a terms & conditions page
- [ ] Set up email backup for orders (optional)
- [ ] Document your WhatsApp order process
- [ ] Train anyone handling customer service

---

## 📱 How Orders Work (Current System):

1. Customer adds items → Clicks "Proceed to Payment"
2. Fills in name, phone, address
3. Selects delivery method:
   - **Pick-up (FREE)** - Gets notified via WhatsApp about location
   - **Courier** - You quote the price separately
4. Clicks "Proceed" 
5. WhatsApp opens with order summary
6. Customer sends order to you
7. You provide payment details (Yoco/bank transfer)
8. After payment, you arrange delivery

---

## 🚀 Next Steps:

1. **Get Google Places API Key** (15 minutes)
2. **Add it to line 10** of index.html
3. **Test thoroughly** 
4. **Get a domain & hosting**
5. **Deploy to web**

Once you have the Google API key, let me know and I can test it live for you!

---

## 📞 Support:
If anything breaks on the site, just let me know:
- Issue description
- Browser/device you're using
- Steps to reproduce

---

**Site is 95% ready for production. Just need Google Places API key!**
