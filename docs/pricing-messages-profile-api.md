# Pricing, messages, and profile API

## Installation

Apply the new migration and rebuild styles on other environments:

```sh
php artisan migrate --path=database/migrations/2026_09_03_000000_add_pricing_contact_fields_and_messages.php --force
npm run build
```

The migration is already applied to this local workspace's database. It adds
`price` and `offer_price` to existing admins/dealers with defaults of `1000.00`
and `800.00`. New records have the same defaults. Values are independent for
each admin and dealer; editing an admin's prices does not overwrite dealer prices.
API prices are decimal strings with two decimal places. Prices accept values
from 0 to 99999999.99.

## Get messages

`GET /api/messages`

Requires a user Bearer token and the same subscription access as FAQs and other
app content. Admin panel sessions are not API Bearer tokens. Obtain the user
token from an existing successful OTP login, Google login, or profile setup.

Complete cURL command (one line, suitable for Postman import or a shell):

```sh
curl --request GET "http://127.0.0.1:8000/api/messages?page=1&per_page=10" --header "Accept: application/json" --header "Authorization: Bearer YOUR_ACCESS_TOKEN"
```

In Windows PowerShell, use `curl.exe` instead of `curl` to avoid its PowerShell alias.
Replace `YOUR_ACCESS_TOKEN` with the actual token and change the base URL for deployment.
Do not send a request body or multipart headers for this GET request.

| Query parameter | Default | Accepted values |
| --- | --- | --- |
| `page` | 1 | Positive integer |
| `per_page` | 10 | Integer from 1 to 100 |

Example response:

```json
{
  "status": true,
  "message": "Messages fetched successfully.",
  "data": {
    "messages": [
      {
        "id": 1,
        "title": "Welcome",
        "message": "Welcome to AdvisorX Pro.",
        "status": true,
        "created_at": "2026-09-03T06:00:00.000000Z",
        "updated_at": "2026-09-03T06:00:00.000000Z"
      }
    ],
    "total": 1,
    "current_page": 1,
    "per_page": 10,
    "last_page": 1
  }
}
```

`data.total` counts **all active messages**, independent of the requested page size.
`data.messages` contains the requested page, newest ID first. Inactive and deleted
messages are excluded. An empty list returns HTTP 200, `messages: []`, and `total: 0`.
Missing/invalid authentication returns 401, blocked subscription access returns
403, and invalid pagination parameters return 422.

Admins can add, edit, activate/deactivate, and delete messages using **Manage Messages**
at `/admin/messages`. A message has a title (maximum 255 characters), message text
(maximum 10000 characters), and status. Message text is treated as plain text.

## Complete profile setup with WhatsApp number and address

`POST /api/auth/complete-setup` accepts optional `whatsapp_number` (string, maximum
30 characters) and `address` (string, maximum 5000 characters). First verify the
email through the existing OTP flow. The successful response includes these
fields in `data.user`, along with the existing access token.

```sh
curl --request POST "http://127.0.0.1:8000/api/auth/complete-setup" --header "Accept: application/json" --form-string "email=user@example.com" --form-string "name=Example User" --form-string "whatsapp_number=+919876543210" --form-string "address=Pune, Maharashtra" --form "profile_photo=@/absolute/path/profile.jpg"
```

Replace the example email with the verified email and the photo path with your
file. The photo is optional; remove its `--form` option when not uploading one.
cURL generates the multipart Content-Type and boundary automatically.

## Update contact details

`POST /api/user/update-profile` accepts the same two optional fields alongside
existing profile fields. Omitted fields keep their values; explicit null or
empty values clear them. Existing subscription access rules apply.

```sh
curl --request POST "http://127.0.0.1:8000/api/user/update-profile" --header "Accept: application/json" --header "Authorization: Bearer YOUR_ACCESS_TOKEN" --form-string "whatsapp_number=+919876543210" --form-string "address=Mumbai, Maharashtra"
```

## Logged-in user, dealer, and admin data

```sh
curl --request GET "http://127.0.0.1:8000/api/user" --header "Accept: application/json" --header "Authorization: Bearer YOUR_ACCESS_TOKEN"
```

- `data.user.whatsapp_number` and `data.user.address` contain saved contact fields.
- Dealer-owned users receive `data.dealer.price` and `data.dealer.offer_price`;
  the dealer also remains nested under `data.user.dealer`.
- Admin-owned users receive `data.admin.price` and `data.admin.offer_price`.
- The existing `data.admin_contact` object now includes both prices when present.
- Login subscription-denial responses include the dealer's prices in
  `data.dealer_subscription` and admin prices in `data.admin_contact`.
- The existing `/api/user` endpoint remains available to authenticated users even
  when their subscription is blocked.

Price fields are editable in the admin profile, admin dealer create/update form,
and dealer profile. The dealer's saved values appear on the expired-subscription
screen. They do not change subscription dates or approval status.
