# Marketing Promotions User Guide

This guide explains how to use Promotional Schemes, Marketing Campaigns, campaign quotas, SGIP distributions, and the Doctor Sample Ledger.

## Overview

The marketing workflow is designed for structured promotions and compliant HCP sample distribution.

Use Promotional Schemes to define the commercial offer or benefit, such as a percentage discount, fixed discount, buy X get Y offer, slab discount, or free goods offer.

Use Marketing Campaigns to plan field execution for a date range, link the campaign to a promotional scheme, define included SGIP items, and allocate territory-level sample quotas.

Use SGIP Distributions to record actual samples, gifts, and promotional inputs given during doctor visits. When a marketing campaign is selected on an SGIP distribution, campaign quota checks run before submission and approval.

Use the Doctor Sample Ledger to review sample distribution history for HCP compliance and audit.

## Navigation

These screens are available under `Marketing & Field Sales`:

- `Promotional Schemes`
- `Marketing Campaigns`
- `SGIP Distributions`
- `Doctor Sample Ledger`

## Promotional Schemes

Promotional Schemes are used to define structured promotional benefits beyond simple line discounts.

### Create a Promotional Scheme

1. Open `Marketing & Field Sales` > `Promotional Schemes`.
2. Click `Create`.
3. Complete `Scheme Details`:
   - `Code`: Unique scheme code.
   - `Name`: Scheme name.
   - `Scheme Type`: Select the kind of promotion.
   - `Status`: Usually start with `Draft`; set to `Active` when ready.
   - `Valid From` and `Valid To`: Optional validity dates.
   - `Minimum Order Value`: Optional minimum order value.
   - `Description`: Optional notes.
4. Complete `Applicability`:
   - `Global`: Applies broadly.
   - `Customer`: Select a customer target.
   - `Territory`: Select a territory target.
   - `Item`: Select an item target.
5. Add one or more `Benefits`:
   - `Benefit Type`: Discount percent, discount amount, free item, buy quantity, or get quantity.
   - `Item`: Optional item linked to the benefit.
   - `Buy Quantity`, `Get Quantity`, `Discount Value`, `Min Quantity`, `Max Quantity`: Fill the values relevant to the selected benefit type.
   - `Remarks`: Optional explanation for the benefit line.
6. Save the scheme.

### Scheme Statuses

- `Draft`: Being prepared.
- `Active`: Available for campaign planning.
- `Expired`: No longer valid.
- `Cancelled`: Withdrawn.

### Important Note

Promotional Schemes currently provide structured planning data and can be linked to Marketing Campaigns. They are not automatically applied to sales document line discounts unless a separate sales-pricing workflow has been implemented.

## Marketing Campaigns

Marketing Campaigns manage campaign-level planning, sample item inclusion, and quota tracking across territories.

### Create a Marketing Campaign

1. Open `Marketing & Field Sales` > `Marketing Campaigns`.
2. Click `Create`.
3. Complete `Campaign Details`:
   - `Campaign Number`: Auto-generated.
   - `Name`: Campaign name.
   - `Status`: Use `Draft` while preparing. Set to `Active` when field execution should begin.
   - `Linked Promotional Scheme`: Optional scheme connected to the campaign.
   - `Start Date` and `End Date`: Required campaign date range.
   - `Total Budget`: Optional campaign budget.
   - `Description`: Optional campaign notes.
4. Add `Campaign Items`:
   - `Item`: Select sample, gift, or promotional input item.
   - `Total Quota`: Total planned campaign quantity for the item.
   - `Unit Value`: Optional item value for planning and reporting.
5. Add `Territory Distribution Quotas`:
   - `Territory`: Territory receiving quota.
   - `Item`: Campaign item being allocated.
   - `Quota`: Quantity allocated to that territory.
   - `Used`: System-tracked quantity consumed through approved SGIP distributions.
6. Save the campaign.

### Campaign Statuses

- `Draft`: Planning is in progress.
- `Active`: Available for selection on SGIP distributions if the current date is within the campaign start and end dates.
- `Closed`: Campaign completed.
- `Cancelled`: Campaign withdrawn.

## SGIP Distribution With Campaign Quota

SGIP Distribution remains the actual document for doctor sample, gift, and promotional input distribution. Marketing campaigns extend this workflow; they do not replace SGIP.

### Record a Campaign Distribution

1. Open `Marketing & Field Sales` > `SGIP Distributions`.
2. Create or edit a draft distribution.
3. Complete `Visit Details`:
   - `Sales Employee`: Defaults to the current user.
   - `Doctor`: Select the HCP/customer.
   - `Visit Date`: Enter the visit date.
   - `Sample Issue Stock`: Required when visit preferences are configured to use posted sample issues.
   - `Marketing Campaign`: Select an active campaign if this distribution belongs to campaign execution.
4. Add `Samples / Gifts / Inputs`:
   - Select the item.
   - Enter quantity.
   - Confirm unit value.
5. Save the draft.
6. Click `Submit`.
7. After review, click `Approve & Deduct Stock`.

### Validation Rules

When submitting or approving an SGIP distribution, the system checks:

- SGIP compliance limits.
- Campaign status and date validity.
- Territory is present when a marketing campaign is selected.
- A territory quota exists for each sample item.
- Requested sample quantity does not exceed remaining campaign territory quota.
- Stock source is configured and sufficient stock is available before inventory is posted.

When approval succeeds, stock is deducted through `InventoryService`, and campaign territory `Used` quantity is increased.

## Doctor Sample Ledger

The Doctor Sample Ledger provides compliance and audit visibility for HCP sample distribution.

### What It Shows

The ledger shows submitted and approved SGIP sample lines, including:

- Visit date
- Doctor/HCP
- Representative
- Territory
- Marketing campaign
- Sample item
- Quantity
- Total value
- SGIP status
- Compliance indicator
- Stock posted timestamp

### Useful Filters

Use the ledger filters to narrow audit review:

- Visit date range
- Marketing campaign
- Territory
- Compliance violations only

## Recommended Operating Flow

1. Create a Promotional Scheme for the offer structure.
2. Create a Marketing Campaign and link it to the scheme.
3. Add campaign items and territory quotas.
4. Set the campaign to `Active` for the valid execution period.
5. Record field distributions through SGIP Distribution.
6. Submit and approve SGIP distributions to deduct stock and consume campaign quota.
7. Review Doctor Sample Ledger for HCP sampling audit and compliance reporting.

## Common Issues

### Campaign is not available on SGIP Distribution

Check that the campaign status is `Active`.

### Campaign quota validation fails

Check that:

- The SGIP distribution has a territory.
- The selected campaign has a quota line for that territory and item.
- Remaining quota is greater than or equal to the requested sample quantity.

### Approval fails due to stock

Check visit preferences and sample stock setup. If SGIP stock source is set to sample issue, a posted Sample Issue must be selected and it must belong to the same representative.

### Doctor Sample Ledger does not show a line

The ledger only shows sample items from submitted or approved SGIP distributions. Draft distributions and non-sample items are not shown.
