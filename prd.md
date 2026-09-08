# Product Requirements Document (PRD): OpenDoors

## 1. Product Overview
**OpenDoors** is a comprehensive, dual-sided marketplace platform for property rentals, similar to Airbnb or Vrbo. It connects property owners/hosts with guests looking for short-term or long-term accommodations. The platform provides end-to-end functionality including property listings, real-time booking, secure payment processing, user wallets, reviews, and identity verification.

## 2. Target Audience
*   **Guests (Users):** Individuals seeking properties to rent for vacations, business trips, or temporary stays.
*   **Hosts (Property Owners):** Individuals or businesses looking to list their properties, manage bookings, and earn revenue.
*   **Administrators & Staff:** Platform operators responsible for managing users, approving documents, handling payouts, and maintaining the system.

## 3. Core Features & Capabilities

### 3.1. User Management & Authentication
*   **Standard Authentication:** Email and password registration/login.
*   **Social Login:** Integration with Google and Apple (utilizing server-side token validation via JWKS).
*   **OTP Verification:** Multi-channel OTP support via Email, SMS (Twilio & Termii integrations).
*   **KYC / Document Verification:** Users and hosts can upload mandatory required documents for identity verification, which must be approved by administrators.
*   **User Profiles:** Profile management including avatars, contact details, and saved preferences.

### 3.2. Property Management (Host & Admin)
*   **Property Listings:** Hosts can add and edit properties with detailed descriptions, pricing, rules, and amenities.
*   **Galleries & Categories:** Upload multiple images per property, categorized appropriately (e.g., Bedroom, Kitchen).
*   **Facilities & Extras:** Define available facilities (e.g., Wi-Fi, Pool) and optional extras that guests can purchase during booking.
*   **Property Subscriptions (Packages):** Hosts must purchase subscription packages that dictate how many properties they can list or for how many days their listings remain active.
*   **Availability Calendar:** Calendar management for blocking dates and viewing upcoming bookings.

### 3.3. Booking & Reservation System
*   **Search & Discovery:** Robust property search with filters by category, location, and availability.
*   **Booking Flow:** Guests can select check-in/check-out dates, number of guests, and add optional extras. 
*   **Pricing Calculation:** Dynamic calculation including base price, subtotal, taxes, and coupon discounts.
*   **Booking Lifecycle:** Manage statuses such as Pending, Confirmed, Check-in, Check-out, and Cancelled.
*   **Third-Party Bookings:** Option to book a property on behalf of someone else (collecting their specific details).

### 3.4. Financials & Wallet System
*   **Digital Wallet:** Every user has a digital wallet that can be topped up and used for booking properties.
*   **Payment Gateways:** Integration with multiple providers including Paystack and potentially others, configured dynamically via the admin panel.
*   **Host Payouts:** Hosts can request withdrawals of their earnings, and admins can process and manage the payout list.
*   **Coupons & Promotions:** Admin-generated promo codes that users can apply during checkout for discounts.
*   **Commissions:** Automated calculation of platform commissions deducted from host earnings.

### 3.5. Engagement & Communication
*   **Notifications:** Real-time push notifications integrated with OneSignal for booking updates, payment confirmations, and system alerts.
*   **Email Engine:** Automated transactional emails via PHPMailer for OTPs, confirmations, and newsletters.
*   **Reviews & Ratings:** Guests can leave reviews and ratings for properties after checkout.
*   **Enquiries:** Built-in messaging/enquiry system for guests to ask hosts questions before booking.
*   **Favorites (Wishlist):** Guests can save properties to a favorites list for future reference.

### 3.6. Admin Dashboard & CMS
*   **Staff Roles:** Role-based access control (RBAC) allowing admins to assign specific permissions to staff members.
*   **Content Management:** Admins can manage dynamic pages, FAQs, and application settings.
*   **Data Management:** Full CRUD capabilities for countries, regions, property categories, facilities, and required document types.
*   **Reporting & Analytics:** View daily sales, overall bookings, and wallet transaction reports.

## 4. Technical Architecture

*   **Backend Framework:** Core PHP (Custom structural MVC/routing based on script endpoints).
*   **Database:** Relational Database (MySQL/MariaDB), accessed via custom `Estate` class wrapper.
*   **API Layer:** Dedicated JSON REST API directory (`user_api/`) servicing mobile applications or frontend SPAs.
*   **Infrastructure Requirements:** PHP 8.0+, Composer (for PHPMailer & Firebase JWT), and standard LAMP/LEMP stack environment.
*   **Third-Party Integrations:**
    *   **Push Notifications:** OneSignal
    *   **Authentication:** Firebase JWT (Apple/Google validation)
    *   **SMS/OTP:** Twilio, Termii
    *   **Payments:** Paystack

## 5. Security Requirements
*   All API endpoints require appropriate session validation or JWT.
*   Strict password hashing using `password_hash()` and `password_verify()`.
*   Sanitization of all inputs to prevent SQL Injection (using `mysqli_real_escape_string` and prepared-like array builders).
*   Environment variable management (`.env`) for sensitive credentials and API keys.

## 6. Future Considerations & Growth
*   Migrate custom PHP routing and DB wrappers to a modern PHP framework (e.g., Laravel) to improve maintainability and security.
*   Implement strict prepared statements (PDO/MySQLi) across all DB interactions to fully eliminate SQL injection risks.
*   Introduce a robust messaging system (e.g., WebSockets) for real-time guest-host chat.
