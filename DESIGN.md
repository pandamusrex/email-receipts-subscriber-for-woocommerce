# PandamusRex Email Webhooks for WooCommerce

## TO DO

- [x] Design tables
- [ ] Rename webhook to webhooks (plural)
- [x] Comment out old post type code
- [ ] Create tables
- [ ] Create persistence layer for each table
- [ ] Allow no-auth POST endpoint writing of email to table
- [ ] Create email list view like memberships
- [ ] Create single email view (read only)
- [ ] Add history list to single email view
- [ ] Add note adding to single email view
- [ ] Add order picking to single email view
- [ ] Add delete to list view
- [ ] Add auth checking to POST endpoint
- [ ] Delete old post type code
- [ ] Add large meta box to order to show associated emails - with links to single email view
- [ ] Write setup guide

- [ ] Add default view to list view to show emails needing disposition
- [ ] Add load more to bottom of list view to load another 50 using jquery dom insertion

## Database schema

- Each forwarded mail needs to be stored and assocated (or not) with an order on the system

```
ID
datetime email received at inbox
email subject
sender email
email body
order ID...
  -1 indicates parked aka marked as not assigned to an order but i don't want to delete it
  0 indicates awaiting order assignment / needs human intervention
  >0 indicates order ID this was assigned to
```

and a history table too

```
ID
email ID
datetime history entry
text of history entry (e.g. webhook received, order assigned)
user id...
  0 indicates the "system" did the action
  >0 indicates the userid of the user that did the action
```