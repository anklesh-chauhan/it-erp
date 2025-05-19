<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $owner_id
 * @property int $type_master_id
 * @property string $name
 * @property string|null $account_code
 * @property string|null $phone_number
 * @property string|null $email
 * @property string|null $secondary_email
 * @property string|null $website
 * @property string|null $no_of_employees
 * @property string|null $twitter
 * @property string|null $linked_in
 * @property string|null $annual_revenue
 * @property string|null $sic_code
 * @property string|null $ticker_symbol
 * @property string|null $description
 * @property int|null $industry_type_id
 * @property int|null $region_id
 * @property int|null $ref_dealer_contact
 * @property string|null $commission
 * @property string|null $category_type
 * @property int|null $category_id
 * @property string|null $typeable_type
 * @property int|null $typeable_id
 * @property string|null $alias
 * @property int|null $parent_id
 * @property int|null $rating_type_id
 * @property int|null $account_ownership_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AccountOwnership|null $accountOwnership
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Address> $addresses
 * @property-read int|null $addresses_count
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent|null $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ContactDetail> $contactDetails
 * @property-read int|null $contact_details_count
 * @property-read \App\Models\ContactDetail|null $dealerName
 * @property-read \App\Models\IndustryType|null $industryType
 * @property-read \App\Models\User $owner
 * @property-read AccountMaster|null $parent
 * @property-read \App\Models\RatingType|null $ratingType
 * @property-read \App\Models\Region|null $region
 * @property-read \App\Models\TypeMaster $typeMaster
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent|null $typeable
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountMaster newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountMaster newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountMaster onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountMaster query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountMaster whereAccountCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountMaster whereAccountOwnershipId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountMaster whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountMaster whereAnnualRevenue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountMaster whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountMaster whereCategoryType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountMaster whereCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountMaster whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountMaster whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountMaster whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountMaster whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountMaster whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountMaster whereIndustryTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountMaster whereLinkedIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountMaster whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountMaster whereNoOfEmployees($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountMaster whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountMaster whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountMaster wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountMaster whereRatingTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountMaster whereRefDealerContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountMaster whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountMaster whereSecondaryEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountMaster whereSicCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountMaster whereTickerSymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountMaster whereTwitter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountMaster whereTypeMasterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountMaster whereTypeableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountMaster whereTypeableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountMaster whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountMaster whereWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountMaster withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountMaster withoutTrashed()
 */
	class AccountMaster extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountOwnership newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountOwnership newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountOwnership query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountOwnership whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountOwnership whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountOwnership whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountOwnership whereUpdatedAt($value)
 */
	class AccountOwnership extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int|null $company_id
 * @property int|null $sort
 * @property string $street
 * @property string|null $area_town
 * @property string $pin_code
 * @property int|null $city_id
 * @property int|null $state_id
 * @property int|null $country_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $contact_detail_id
 * @property string|null $address_type
 * @property string|null $addressable_type
 * @property int|null $type_master_id
 * @property int|null $addressable_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AccountMaster> $accountMasters
 * @property-read int|null $account_masters_count
 * @property-read \App\Models\TypeMaster|null $addressType
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent|null $addressable
 * @property-read \App\Models\City|null $city
 * @property-read \App\Models\Company|null $company
 * @property-read \App\Models\ContactDetail|null $contactDetail
 * @property-read \App\Models\Country|null $country
 * @property-read \App\Models\State|null $state
 * @property-read \App\Models\TypeMaster|null $typeMaster
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereAddressType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereAddressableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereAddressableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereAreaTown($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereContactDetailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address wherePinCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereTypeMasterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereUpdatedAt($value)
 */
	class Address extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Address> $addresses
 * @property-read int|null $addresses_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressType whereUpdatedAt($value)
 */
	class AddressType extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string|null $attachable_type
 * @property int|null $attachable_id
 * @property string $file_name
 * @property string $file_path
 * @property string|null $file_type
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent|null $attachable
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereAttachableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereAttachableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereFileType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereUpdatedAt($value)
 */
	class Attachment extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $alias
 * @property int|null $parent_id
 * @property string|null $description
 * @property string|null $image_path
 * @property string|null $modelable_type
 * @property int|null $modelable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Category> $children
 * @property-read int|null $children_count
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent|null $modelable
 * @property-read Category|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Category> $subCategories
 * @property-read int|null $sub_categories_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category ofType(string $model)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereModelableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereModelableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category withoutTrashed()
 */
	class Category extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property int $state_id
 * @property int $country_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Country $country
 * @property-read \App\Models\CityPinCode|null $pinCode
 * @property-read \App\Models\State $state
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereUpdatedAt($value)
 */
	class City extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $pin_code
 * @property string|null $area_town
 * @property int $city_id
 * @property int $state_id
 * @property int $country_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\City $city
 * @property-read \App\Models\Country $country
 * @property-read \App\Models\State $state
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CityPinCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CityPinCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CityPinCode query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CityPinCode whereAreaTown($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CityPinCode whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CityPinCode whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CityPinCode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CityPinCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CityPinCode wherePinCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CityPinCode whereStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CityPinCode whereUpdatedAt($value)
 */
	class CityPinCode extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $phone_number
 * @property string|null $email
 * @property string|null $secondary_email
 * @property string|null $website
 * @property string|null $no_of_employees
 * @property string|null $twitter
 * @property string|null $linked_in
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $industry_type_id
 * @property int|null $account_master_id
 * @property-read \App\Models\AccountMaster|null $accountMaster
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Address> $addresses
 * @property-read int|null $addresses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ContactDetail> $contactDetails
 * @property-read int|null $contact_details_count
 * @property-read \App\Models\IndustryType|null $industryType
 * @property-read \App\Models\ItemMaster|null $itemMaster
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereAccountMasterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereIndustryTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereLinkedIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereNoOfEmployees($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereSecondaryEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereTwitter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereWebsite($value)
 */
	class Company extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $company_id
 * @property int|null $contact_details_id
 * @property int|null $region_id
 * @property int|null $company_master_type_id
 * @property string|null $vendor_code
 * @property string|null $company_code
 * @property int|null $address_id
 * @property int|null $dealer_name_id
 * @property string|null $commission
 * @property string|null $category_type
 * @property int|null $category_id
 * @property string|null $typeable_type
 * @property int|null $typeable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Address|null $address
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attachment> $attachments
 * @property-read int|null $attachments_count
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent|null $category
 * @property-read \App\Models\Company $company
 * @property-read \App\Models\CompanyMasterType|null $companyMasterType
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ContactDetail> $contactDetails
 * @property-read int|null $contact_details_count
 * @property-read \App\Models\ContactDetail|null $dealerName
 * @property-read \App\Models\NumberSeries|null $numberSeries
 * @property-read \App\Models\Region|null $region
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent|null $typeable
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMaster newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMaster newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMaster onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMaster query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMaster whereAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMaster whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMaster whereCategoryType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMaster whereCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMaster whereCompanyCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMaster whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMaster whereCompanyMasterTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMaster whereContactDetailsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMaster whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMaster whereDealerNameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMaster whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMaster whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMaster whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMaster whereTypeableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMaster whereTypeableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMaster whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMaster whereVendorCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMaster withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMaster withoutTrashed()
 */
	class CompanyMaster extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int|null $company_master_id
 * @property string $bank_name
 * @property string $account_number
 * @property string $ifsc_code
 * @property string $name_in_bank
 * @property string|null $remarks
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\CompanyMaster|null $companyMaster
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMasterBankDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMasterBankDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMasterBankDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMasterBankDetail whereAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMasterBankDetail whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMasterBankDetail whereCompanyMasterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMasterBankDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMasterBankDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMasterBankDetail whereIfscCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMasterBankDetail whereNameInBank($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMasterBankDetail whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMasterBankDetail whereUpdatedAt($value)
 */
	class CompanyMasterBankDetail extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int|null $company_master_id
 * @property int|null $credit_days
 * @property string|null $credit_limit
 * @property string|null $cin
 * @property string|null $tds_parameters
 * @property int $is_tds_deduct
 * @property int $is_tds_compulsory
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\CompanyMaster|null $companyMaster
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMasterStatutoryDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMasterStatutoryDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMasterStatutoryDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMasterStatutoryDetail whereCin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMasterStatutoryDetail whereCompanyMasterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMasterStatutoryDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMasterStatutoryDetail whereCreditDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMasterStatutoryDetail whereCreditLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMasterStatutoryDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMasterStatutoryDetail whereIsTdsCompulsory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMasterStatutoryDetail whereIsTdsDeduct($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMasterStatutoryDetail whereTdsParameters($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMasterStatutoryDetail whereUpdatedAt($value)
 */
	class CompanyMasterStatutoryDetail extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMasterType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMasterType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMasterType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMasterType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMasterType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMasterType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyMasterType whereUpdatedAt($value)
 */
	class CompanyMasterType extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int|null $company_id
 * @property string|null $salutation
 * @property string $first_name
 * @property string|null $last_name
 * @property string|null $email
 * @property string|null $mobile_number
 * @property string|null $whatsapp_number
 * @property string|null $alternate_phone
 * @property \App\Models\Designation|null $designation
 * @property \App\Models\Department|null $department
 * @property string|null $address
 * @property string|null $city
 * @property string|null $state
 * @property string|null $country
 * @property string|null $postal_code
 * @property string|null $birthday
 * @property string|null $linkedin
 * @property string|null $facebook
 * @property string|null $twitter
 * @property string|null $website
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $designation_id
 * @property int|null $department_id
 * @property string|null $contactable_type
 * @property int|null $contactable_id
 * @property int|null $account_master_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AccountMaster> $accountMaster
 * @property-read int|null $account_master_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Address> $addresses
 * @property-read int|null $addresses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CompanyMaster> $companies
 * @property-read int|null $companies_count
 * @property-read \App\Models\Company|null $company
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CompanyMaster> $companyMasters
 * @property-read int|null $company_masters_count
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent|null $contactable
 * @property-read string $full_name
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactDetail search($searchTerm)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactDetail upcomingBirthdays()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactDetail whereAccountMasterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactDetail whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactDetail whereAlternatePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactDetail whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactDetail whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactDetail whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactDetail whereContactableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactDetail whereContactableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactDetail whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactDetail whereDepartment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactDetail whereDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactDetail whereDesignation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactDetail whereDesignationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactDetail whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactDetail whereFacebook($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactDetail whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactDetail whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactDetail whereLinkedin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactDetail whereMobileNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactDetail whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactDetail wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactDetail whereSalutation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactDetail whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactDetail whereTwitter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactDetail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactDetail whereWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactDetail whereWhatsappNumber($value)
 */
	class ContactDetail extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\State> $states
 * @property-read int|null $states_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereUpdatedAt($value)
 */
	class Country extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $reference_code
 * @property string $deal_name
 * @property string $transaction_date
 * @property int $owner_id
 * @property int|null $contact_detail_id
 * @property int|null $company_id
 * @property int|null $address_id
 * @property string|null $type_type
 * @property int|null $type_id
 * @property string $amount
 * @property string $expected_revenue
 * @property string $expected_close_date
 * @property int|null $lead_source_id
 * @property string|null $description
 * @property string $status_type
 * @property int $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Address|null $address
 * @property-read \App\Models\Company|null $company
 * @property-read \App\Models\ContactDetail|null $contact
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ContactDetail> $contactComapnyDetails
 * @property-read int|null $contact_comapny_details_count
 * @property-read \App\Models\ContactDetail|null $contactDetail
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LeadCustomField> $customFields
 * @property-read int|null $custom_fields_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FollowUp> $followUps
 * @property-read int|null $follow_ups_count
 * @property-read mixed $display_name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ItemMaster> $itemMasters
 * @property-read int|null $item_masters_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LeadActivity> $leadActivities
 * @property-read int|null $lead_activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LeadNote> $leadNotes
 * @property-read int|null $lead_notes_count
 * @property-read \App\Models\LeadSource|null $leadSource
 * @property-read \App\Models\User $owner
 * @property-read \App\Models\RatingType|null $rating
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $status
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent|null $type
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal whereAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal whereContactDetailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal whereDealName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal whereExpectedCloseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal whereExpectedRevenue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal whereLeadSourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal whereReferenceCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal whereStatusType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal whereTypeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal whereUpdatedAt($value)
 */
	class Deal extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $color
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $statusable
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DealStage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DealStage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DealStage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DealStage whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DealStage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DealStage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DealStage whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DealStage whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DealStage whereUpdatedAt($value)
 */
	class DealStage extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $document_number
 * @property int|null $lead_id
 * @property int|null $deal_id
 * @property int $contact_detail_id
 * @property int $account_master_id
 * @property int|null $billing_address_id
 * @property int|null $shipping_address_id
 * @property string $date
 * @property string $status
 * @property int|null $sales_person_id
 * @property string $subtotal
 * @property string $tax
 * @property string $total
 * @property string $currency
 * @property string|null $payment_terms
 * @property string|null $payment_method
 * @property string|null $shipping_method
 * @property string|null $shipping_cost
 * @property string|null $description
 * @property string|null $rejected_at
 * @property string|null $canceled_at
 * @property string|null $sent_at
 * @property int $created_by
 * @property int $updated_by
 * @property int|null $deleted_by
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryChallan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryChallan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryChallan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryChallan whereAccountMasterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryChallan whereBillingAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryChallan whereCanceledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryChallan whereContactDetailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryChallan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryChallan whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryChallan whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryChallan whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryChallan whereDealId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryChallan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryChallan whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryChallan whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryChallan whereDocumentNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryChallan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryChallan whereLeadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryChallan wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryChallan wherePaymentTerms($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryChallan whereRejectedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryChallan whereSalesPersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryChallan whereSentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryChallan whereShippingAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryChallan whereShippingCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryChallan whereShippingMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryChallan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryChallan whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryChallan whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryChallan whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryChallan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryChallan whereUpdatedBy($value)
 */
	class DeliveryChallan extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ContactDetail> $contactDetails
 * @property-read int|null $contact_details_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department whereUpdatedAt($value)
 */
	class Department extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ContactDetail> $contactDetails
 * @property-read int|null $contact_details_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Designation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Designation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Designation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Designation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Designation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Designation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Designation whereUpdatedAt($value)
 */
	class Designation extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $category_id
 * @property int $expense_type_id
 * @property int|null $transport_mode_id
 * @property string|null $rate_per_km
 * @property string|null $fixed_expense
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $category
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseConfiguration newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseConfiguration newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseConfiguration query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseConfiguration whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseConfiguration whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseConfiguration whereExpenseTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseConfiguration whereFixedExpense($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseConfiguration whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseConfiguration whereRatePerKm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseConfiguration whereTransportModeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseConfiguration whereUpdatedAt($value)
 */
	class ExpenseConfiguration extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseType whereUpdatedAt($value)
 */
	class ExpenseType extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $followupable_type
 * @property int $followupable_id
 * @property int $user_id
 * @property string|null $follow_up_date
 * @property string|null $interaction
 * @property string|null $outcome
 * @property string|null $next_follow_up_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $to_whom
 * @property int|null $follow_up_media_id
 * @property int|null $follow_up_result_id
 * @property int|null $follow_up_status_id
 * @property int|null $follow_up_priority_id
 * @property-read \App\Models\ContactDetail|null $contactDetail
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $followupable
 * @property-read \App\Models\Lead|null $lead
 * @property-read \App\Models\FollowUpMedia|null $media
 * @property-read \App\Models\FollowUpPriority|null $priority
 * @property-read \App\Models\FollowUpResult|null $result
 * @property-read \App\Models\FollowUpStatus|null $status
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUp query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUp whereFollowUpDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUp whereFollowUpMediaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUp whereFollowUpPriorityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUp whereFollowUpResultId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUp whereFollowUpStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUp whereFollowupableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUp whereFollowupableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUp whereInteraction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUp whereNextFollowUpDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUp whereOutcome($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUp whereToWhom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUp whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUp whereUserId($value)
 */
	class FollowUp extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FollowUp> $followUps
 * @property-read int|null $follow_ups_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUpMedia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUpMedia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUpMedia query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUpMedia whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUpMedia whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUpMedia whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUpMedia whereUpdatedAt($value)
 */
	class FollowUpMedia extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FollowUp> $followUps
 * @property-read int|null $follow_ups_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUpPriority newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUpPriority newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUpPriority query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUpPriority whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUpPriority whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUpPriority whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUpPriority whereUpdatedAt($value)
 */
	class FollowUpPriority extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FollowUp> $followUps
 * @property-read int|null $follow_ups_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUpResult newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUpResult newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUpResult query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUpResult whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUpResult whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUpResult whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUpResult whereUpdatedAt($value)
 */
	class FollowUpResult extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FollowUp> $followUps
 * @property-read int|null $follow_ups_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUpStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUpStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUpStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUpStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUpStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUpStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FollowUpStatus whereUpdatedAt($value)
 */
	class FollowUpStatus extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int|null $company_master_id
 * @property int $company_id
 * @property int $address_id
 * @property string $pan_number
 * @property string $gst_number
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Address $address
 * @property-read \App\Models\Company $company
 * @property-read \App\Models\CompanyMaster|null $companyMaster
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GstPan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GstPan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GstPan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GstPan whereAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GstPan whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GstPan whereCompanyMasterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GstPan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GstPan whereGstNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GstPan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GstPan wherePanNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GstPan whereUpdatedAt($value)
 */
	class GstPan extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string|null $imageable_type
 * @property int|null $imageable_id
 * @property string $file_name
 * @property string $file_path
 * @property string|null $file_type
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent|null $imageable
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image whereFileType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image whereImageableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image whereImageableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image whereUpdatedAt($value)
 */
	class Image extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndustryType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndustryType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndustryType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndustryType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndustryType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndustryType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndustryType whereUpdatedAt($value)
 */
	class IndustryType extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemBrand newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemBrand newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemBrand query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemBrand whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemBrand whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemBrand whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemBrand whereUpdatedAt($value)
 */
	class ItemBrand extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemCategory query()
 */
	class ItemCategory extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $item_code
 * @property string $item_name
 * @property string|null $description
 * @property string|null $category_type
 * @property int|null $category_id
 * @property int|null $item_brand_id
 * @property string|null $purchase_price
 * @property string|null $selling_price
 * @property string|null $hsn_code
 * @property string|null $tax_rate
 * @property int|null $discount
 * @property int|null $opening_stock
 * @property int|null $minimum_stock_level
 * @property int|null $reorder_quantity
 * @property int|null $unit_of_measurement_id
 * @property int|null $lead_time
 * @property string|null $storage_location
 * @property string|null $barcode
 * @property string|null $expiry_date
 * @property int|null $packaging_type_id
 * @property int|null $per_packing_qty
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attachment> $attachments
 * @property-read int|null $attachments_count
 * @property-read \App\Models\ItemBrand|null $brand
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent|null $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Image> $images
 * @property-read int|null $images_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lead> $leads
 * @property-read int|null $leads_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LocationMaster> $locations
 * @property-read int|null $locations_count
 * @property-read \App\Models\PackagingType|null $packagingType
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AccountMaster> $suppliers
 * @property-read int|null $suppliers_count
 * @property-read \App\Models\UnitOfMeasurement|null $unitOfMeasurement
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemMaster newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemMaster newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemMaster onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemMaster query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemMaster whereBarcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemMaster whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemMaster whereCategoryType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemMaster whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemMaster whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemMaster whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemMaster whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemMaster whereExpiryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemMaster whereHsnCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemMaster whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemMaster whereItemBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemMaster whereItemCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemMaster whereItemName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemMaster whereLeadTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemMaster whereMinimumStockLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemMaster whereOpeningStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemMaster wherePackagingTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemMaster wherePerPackingQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemMaster wherePurchasePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemMaster whereReorderQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemMaster whereSellingPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemMaster whereStorageLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemMaster whereTaxRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemMaster whereUnitOfMeasurementId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemMaster whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemMaster withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemMaster withoutTrashed()
 */
	class ItemMaster extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $item_master_id
 * @property int $unit_of_measurement_id
 * @property string $conversion_rate
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ItemMaster $itemMaster
 * @property-read \App\Models\UnitOfMeasurement $unitOfMeasurement
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemMeasurementUnit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemMeasurementUnit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemMeasurementUnit query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemMeasurementUnit whereConversionRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemMeasurementUnit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemMeasurementUnit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemMeasurementUnit whereItemMasterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemMeasurementUnit whereUnitOfMeasurementId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemMeasurementUnit whereUpdatedAt($value)
 */
	class ItemMeasurementUnit extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $reference_code
 * @property string|null $transaction_date
 * @property int $owner_id
 * @property int|null $contact_detail_id
 * @property int|null $company_id
 * @property int|null $address_id
 * @property int|null $lead_source_id
 * @property string $status_type
 * @property int $status_id
 * @property int|null $rating_type_id
 * @property string|null $annual_revenue
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property array<array-key, mixed>|null $custom_fields
 * @property-read \App\Models\Address|null $address
 * @property-read \App\Models\Company|null $company
 * @property-read \App\Models\ContactDetail|null $contact
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ContactDetail> $contactComapnyDetails
 * @property-read int|null $contact_comapny_details_count
 * @property-read \App\Models\ContactDetail|null $contactDetail
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LeadCustomField> $customFields
 * @property-read int|null $custom_fields_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FollowUp> $followUps
 * @property-read int|null $follow_ups_count
 * @property-read mixed $display_name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ItemMaster> $itemMasters
 * @property-read int|null $item_masters_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LeadActivity> $leadActivities
 * @property-read int|null $lead_activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LeadNote> $leadNotes
 * @property-read int|null $lead_notes_count
 * @property-read \App\Models\LeadSource|null $leadSource
 * @property-read \App\Models\User $owner
 * @property-read \App\Models\RatingType|null $rating
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereAnnualRevenue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereContactDetailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereCustomFields($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereLeadSourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereRatingTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereReferenceCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereStatusType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereUpdatedAt($value)
 */
	class Lead extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $lead_id
 * @property int|null $user_id
 * @property string $activity_type
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Lead $lead
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadActivity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadActivity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadActivity query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadActivity whereActivityType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadActivity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadActivity whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadActivity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadActivity whereLeadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadActivity whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadActivity whereUserId($value)
 */
	class LeadActivity extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $label
 * @property string $type
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadCustomField newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadCustomField newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadCustomField query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadCustomField whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadCustomField whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadCustomField whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadCustomField whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadCustomField whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadCustomField whereUpdatedAt($value)
 */
	class LeadCustomField extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $lead_id
 * @property int $user_id
 * @property string $note
 * @property string|null $attachment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attachment> $attachments
 * @property-read int|null $attachments_count
 * @property-read \App\Models\Lead $lead
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadNote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadNote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadNote query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadNote whereAttachment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadNote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadNote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadNote whereLeadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadNote whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadNote whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadNote whereUserId($value)
 */
	class LeadNote extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadSource newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadSource newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadSource query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadSource whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadSource whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadSource whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadSource whereUpdatedAt($value)
 */
	class LeadSource extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $color
 * @property int|null $order
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadStatus whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadStatus whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeadStatus whereUpdatedAt($value)
 */
	class LeadStatus extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $location_code
 * @property string|null $description
 * @property string|null $typeable_type
 * @property int|null $typeable_id
 * @property int $is_active
 * @property string|null $addressable_type
 * @property int|null $addressable_id
 * @property string|null $contactable_type
 * @property int|null $contactable_id
 * @property string|null $latitude
 * @property string|null $longitude
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $parent_id
 * @property-read \App\Models\Address|null $address
 * @property-read \App\Models\ContactDetail|null $contactDetail
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ItemMaster> $items
 * @property-read int|null $items_count
 * @property-read LocationMaster|null $parentLocation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, LocationMaster> $subLocations
 * @property-read int|null $sub_locations_count
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent|null $typeable
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LocationMaster newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LocationMaster newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LocationMaster query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LocationMaster whereAddressableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LocationMaster whereAddressableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LocationMaster whereContactableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LocationMaster whereContactableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LocationMaster whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LocationMaster whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LocationMaster whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LocationMaster whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LocationMaster whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LocationMaster whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LocationMaster whereLocationCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LocationMaster whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LocationMaster whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LocationMaster whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LocationMaster whereTypeableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LocationMaster whereTypeableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LocationMaster whereUpdatedAt($value)
 */
	class LocationMaster extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string|null $modelable_type
 * @property int|null $modelable_id
 * @property string|null $model_type
 * @property string|null $Prefix
 * @property int $next_number
 * @property string|null $Suffix
 * @property int|null $type_master_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent|null $modelable
 * @property-read \App\Models\TypeMaster|null $typeMaster
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NumberSeries newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NumberSeries newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NumberSeries query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NumberSeries whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NumberSeries whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NumberSeries whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NumberSeries whereModelableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NumberSeries whereModelableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NumberSeries whereNextNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NumberSeries wherePrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NumberSeries whereSuffix($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NumberSeries whereTypeMasterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NumberSeries whereUpdatedAt($value)
 */
	class NumberSeries extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackagingType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackagingType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackagingType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackagingType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackagingType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackagingType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackagingType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackagingType whereUpdatedAt($value)
 */
	class PackagingType extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackingType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackingType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackingType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackingType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackingType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackingType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackingType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackingType whereUpdatedAt($value)
 */
	class PackingType extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission withoutRole($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission withoutTrashed()
 */
	class Permission extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $document_number
 * @property int|null $lead_id
 * @property int|null $deal_id
 * @property int $contact_detail_id
 * @property int $company_id
 * @property int|null $billing_address_id
 * @property int|null $shipping_address_id
 * @property string $date
 * @property \Illuminate\Support\Carbon|null $expiration_date
 * @property string $status
 * @property int|null $sales_person_id
 * @property string $subtotal
 * @property string $tax
 * @property string $total
 * @property string $currency
 * @property string|null $payment_terms
 * @property string|null $payment_method
 * @property string|null $shipping_method
 * @property string|null $shipping_cost
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $accepted_at
 * @property string|null $rejected_at
 * @property string|null $canceled_at
 * @property string|null $sent_at
 * @property int $created_by
 * @property int $updated_by
 * @property int|null $deleted_by
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Address|null $address
 * @property-read \App\Models\Address|null $billingAddress
 * @property-read \App\Models\Company $company
 * @property-read \App\Models\ContactDetail $contactDetail
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SalesDocumentItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\Lead|null $lead
 * @property-read \App\Models\User|null $salesPerson
 * @property-read \App\Models\Address|null $shippingAddress
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereAcceptedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereBillingAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereCanceledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereContactDetailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereDealId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereDocumentNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereExpirationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereLeadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote wherePaymentTerms($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereRejectedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereSalesPersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereSentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereShippingAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereShippingCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereShippingMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereUpdatedBy($value)
 */
	class Quote extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RatingType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RatingType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RatingType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RatingType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RatingType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RatingType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RatingType whereUpdatedAt($value)
 */
	class RatingType extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region whereUpdatedAt($value)
 */
	class Region extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role withoutTrashed()
 */
	class Role extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $serial_number
 * @property string $expense_date
 * @property int $user_id
 * @property string $transaction_date
 * @property string|null $category_type
 * @property int|null $category_id
 * @property int|null $expense_type_id
 * @property int|null $tour_plan_id
 * @property string|null $rate_amount
 * @property string|null $claim_amount
 * @property string|null $approved_amount
 * @property int|null $approver_id
 * @property string|null $remarks
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDailyExpense newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDailyExpense newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDailyExpense query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDailyExpense whereApprovedAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDailyExpense whereApproverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDailyExpense whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDailyExpense whereCategoryType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDailyExpense whereClaimAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDailyExpense whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDailyExpense whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDailyExpense whereExpenseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDailyExpense whereExpenseTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDailyExpense whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDailyExpense whereRateAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDailyExpense whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDailyExpense whereSerialNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDailyExpense whereTourPlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDailyExpense whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDailyExpense whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDailyExpense whereUserId($value)
 */
	class SalesDailyExpense extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $date
 * @property int $user_id
 * @property array<array-key, mixed>|null $jointwork_user_ids
 * @property int|null $visit_type_id
 * @property int|null $tour_plan_id
 * @property array<array-key, mixed>|null $visit_route_ids
 * @property string|null $category_type
 * @property int|null $category_id
 * @property string|null $expense_total
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\TourPlan|null $tourPlan
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDcr newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDcr newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDcr onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDcr query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDcr whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDcr whereCategoryType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDcr whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDcr whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDcr whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDcr whereExpenseTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDcr whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDcr whereJointworkUserIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDcr whereTourPlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDcr whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDcr whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDcr whereVisitRouteIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDcr whereVisitTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDcr withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDcr withoutTrashed()
 */
	class SalesDcr extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string|null $document_type
 * @property int|null $document_id
 * @property int|null $item_master_id
 * @property int $quantity
 * @property numeric $price
 * @property string|null $discount
 * @property string|null $unit
 * @property string|null $unit_price
 * @property string|null $hsn_sac
 * @property string $tax_rate
 * @property string $amount
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent|null $document
 * @property-read \App\Models\ItemMaster|null $itemMaster
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDocumentItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDocumentItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDocumentItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDocumentItem whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDocumentItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDocumentItem whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDocumentItem whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDocumentItem whereDocumentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDocumentItem whereDocumentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDocumentItem whereHsnSac($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDocumentItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDocumentItem whereItemMasterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDocumentItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDocumentItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDocumentItem whereTaxRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDocumentItem whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDocumentItem whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesDocumentItem whereUpdatedAt($value)
 */
	class SalesDocumentItem extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $document_number
 * @property int|null $lead_id
 * @property int|null $deal_id
 * @property int $contact_detail_id
 * @property int $account_master_id
 * @property int|null $billing_address_id
 * @property int|null $shipping_address_id
 * @property string $date
 * @property string $status
 * @property int|null $sales_person_id
 * @property string $subtotal
 * @property string $tax
 * @property string $total
 * @property string $currency
 * @property string|null $payment_terms
 * @property string|null $payment_method
 * @property string|null $shipping_method
 * @property string|null $shipping_cost
 * @property string|null $description
 * @property string|null $accepted_at
 * @property string|null $rejected_at
 * @property string|null $canceled_at
 * @property string|null $sent_at
 * @property \Illuminate\Support\Carbon|null $due_date
 * @property string|null $payment_status
 * @property \Illuminate\Support\Carbon|null $paid_at
 * @property int $created_by
 * @property int $updated_by
 * @property int|null $deleted_by
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AccountMaster $accountMaster
 * @property-read \App\Models\Address|null $address
 * @property-read \App\Models\Address|null $billingAddress
 * @property-read \App\Models\Company|null $company
 * @property-read \App\Models\ContactDetail $contactDetail
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SalesDocumentItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\Lead|null $lead
 * @property-read \App\Models\User|null $salesPerson
 * @property-read \App\Models\Address|null $shippingAddress
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesInvoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesInvoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesInvoice query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesInvoice whereAcceptedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesInvoice whereAccountMasterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesInvoice whereBillingAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesInvoice whereCanceledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesInvoice whereContactDetailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesInvoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesInvoice whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesInvoice whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesInvoice whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesInvoice whereDealId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesInvoice whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesInvoice whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesInvoice whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesInvoice whereDocumentNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesInvoice whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesInvoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesInvoice whereLeadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesInvoice wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesInvoice wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesInvoice wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesInvoice wherePaymentTerms($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesInvoice whereRejectedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesInvoice whereSalesPersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesInvoice whereSentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesInvoice whereShippingAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesInvoice whereShippingCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesInvoice whereShippingMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesInvoice whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesInvoice whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesInvoice whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesInvoice whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesInvoice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesInvoice whereUpdatedBy($value)
 */
	class SalesInvoice extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $document_number
 * @property int|null $lead_id
 * @property int|null $deal_id
 * @property int $contact_detail_id
 * @property int $account_master_id
 * @property int|null $billing_address_id
 * @property int|null $shipping_address_id
 * @property string $date
 * @property string|null $expiration_date
 * @property string $status
 * @property int|null $sales_person_id
 * @property string $subtotal
 * @property string $tax
 * @property string $total
 * @property string $currency
 * @property string|null $payment_terms
 * @property string|null $payment_method
 * @property string|null $shipping_method
 * @property string|null $shipping_cost
 * @property string|null $description
 * @property string|null $accepted_at
 * @property string|null $rejected_at
 * @property string|null $canceled_at
 * @property string|null $sent_at
 * @property \Illuminate\Support\Carbon|null $delivery_date
 * @property \Illuminate\Support\Carbon|null $order_confirmation_at
 * @property int $created_by
 * @property int $updated_by
 * @property int|null $deleted_by
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AccountMaster $accountMaster
 * @property-read \App\Models\Address|null $address
 * @property-read \App\Models\Address|null $billingAddress
 * @property-read \App\Models\Company|null $company
 * @property-read \App\Models\ContactDetail $contactDetail
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SalesDocumentItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\Lead|null $lead
 * @property-read \App\Models\User|null $salesPerson
 * @property-read \App\Models\Address|null $shippingAddress
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereAcceptedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereAccountMasterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereBillingAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereCanceledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereContactDetailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereDealId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereDeliveryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereDocumentNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereExpirationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereLeadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereOrderConfirmationAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder wherePaymentTerms($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereRejectedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereSalesPersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereSentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereShippingAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereShippingCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereShippingMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereUpdatedBy($value)
 */
	class SalesOrder extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property int $country_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Country $country
 * @method static \Illuminate\Database\Eloquent\Builder<static>|State newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|State newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|State query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|State whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|State whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|State whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|State whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|State whereUpdatedAt($value)
 */
	class State extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $domain
 * @property string $database
 * @property string|null $cloudflare_record_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Spatie\Multitenancy\TenantCollection<int, static> all($columns = ['*'])
 * @method static \Spatie\Multitenancy\TenantCollection<int, static> get($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereCloudflareRecordId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereDatabase($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereUpdatedAt($value)
 */
	class Tenant extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TenantUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TenantUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TenantUser permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TenantUser query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TenantUser role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TenantUser withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TenantUser withoutRole($roles, $guard = null)
 */
	class TenantUser extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string|null $model_type
 * @property int|null $model_id
 * @property int|null $terms_type_id
 * @property string $terms_and_conditions
 * @property string|null $remarks
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent|null $model
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TermsAndCondition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TermsAndCondition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TermsAndCondition query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TermsAndCondition whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TermsAndCondition whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TermsAndCondition whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TermsAndCondition whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TermsAndCondition whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TermsAndCondition whereTermsAndConditions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TermsAndCondition whereTermsTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TermsAndCondition whereUpdatedAt($value)
 */
	class TermsAndCondition extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TermsType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TermsType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TermsType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TermsType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TermsType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TermsType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TermsType whereUpdatedAt($value)
 */
	class TermsType extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string $plan_date
 * @property string $location
 * @property string $start_time
 * @property string $end_time
 * @property int|null $visit_purpose_id
 * @property string|null $target_customer
 * @property string|null $notes
 * @property string|null $mode_of_transport
 * @property string|null $distance_travelled
 * @property string|null $travel_expenses
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $user
 * @property-read \App\Models\VisitPurpose|null $visitPurpose
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\VisitRoute> $visitRoutes
 * @property-read int|null $visit_routes_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourPlan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourPlan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourPlan onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourPlan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourPlan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourPlan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourPlan whereDistanceTravelled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourPlan whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourPlan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourPlan whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourPlan whereModeOfTransport($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourPlan whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourPlan wherePlanDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourPlan whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourPlan whereTargetCustomer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourPlan whereTravelExpenses($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourPlan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourPlan whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourPlan whereVisitPurposeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourPlan withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TourPlan withoutTrashed()
 */
	class TourPlan extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransportMode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransportMode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransportMode query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransportMode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransportMode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransportMode whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransportMode whereUpdatedAt($value)
 */
	class TransportMode extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $typeable_type
 * @property int|null $typeable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent|null $typeable
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TypeMaster newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TypeMaster newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TypeMaster ofType(string $model)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TypeMaster query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TypeMaster whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TypeMaster whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TypeMaster whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TypeMaster whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TypeMaster whereTypeableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TypeMaster whereTypeableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TypeMaster whereUpdatedAt($value)
 */
	class TypeMaster extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $abbreviation
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitOfMeasurement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitOfMeasurement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitOfMeasurement query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitOfMeasurement whereAbbreviation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitOfMeasurement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitOfMeasurement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitOfMeasurement whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitOfMeasurement whereUpdatedAt($value)
 */
	class UnitOfMeasurement extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitPurpose newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitPurpose newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitPurpose query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitPurpose whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitPurpose whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitPurpose whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitPurpose whereUpdatedAt($value)
 */
	class VisitPurpose extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property int $user_id
 * @property string $route_date
 * @property int|null $lead_id
 * @property int|null $contact_detail_id
 * @property int|null $company_id
 * @property int|null $address_id
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Address|null $address
 * @property-read \App\Models\Company|null $company
 * @property-read \App\Models\ContactDetail|null $contactDetail
 * @property-read \App\Models\Lead|null $lead
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TourPlan> $tourPlans
 * @property-read int|null $tour_plans_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitRoute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitRoute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitRoute onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitRoute query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitRoute whereAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitRoute whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitRoute whereContactDetailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitRoute whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitRoute whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitRoute whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitRoute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitRoute whereLeadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitRoute whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitRoute whereRouteDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitRoute whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitRoute whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitRoute withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitRoute withoutTrashed()
 */
	class VisitRoute extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $visit_route_id
 * @property int $tour_plan_id
 * @property int $visit_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\TourPlan $tourPlan
 * @property-read \App\Models\VisitRoute $visitRoute
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitRouteTourPlan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitRouteTourPlan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitRouteTourPlan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitRouteTourPlan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitRouteTourPlan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitRouteTourPlan whereTourPlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitRouteTourPlan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitRouteTourPlan whereVisitOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitRouteTourPlan whereVisitRouteId($value)
 */
	class VisitRouteTourPlan extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitType whereUpdatedAt($value)
 */
	class VisitType extends \Eloquent {}
}

