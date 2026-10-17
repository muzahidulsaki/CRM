<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\Quotation;
use Illuminate\Support\Facades\DB;

class LeadService
{
    /**
     * Generate unique sequential Lead Code: LD-YYYY-XXXXX
     */
    public static function generateLeadCode(): string
    {
        $year = date('Y');
        $prefix = "LD-{$year}-";

        $lastLead = Lead::withTrashed()
            ->where('lead_code', 'like', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->first();

        $sequence = 1;
        if ($lastLead && preg_match('/LD-\d{4}-(\d+)/', $lastLead->lead_code, $matches)) {
            $sequence = intval($matches[1]) + 1;
        }

        return sprintf('%s%05d', $prefix, $sequence);
    }

    /**
     * Generate unique sequential Customer Code: CUST-YYYY-XXXXX
     */
    public static function generateCustomerCode(): string
    {
        $year = date('Y');
        $prefix = "CUST-{$year}-";

        $lastCustomer = Customer::withTrashed()
            ->where('customer_code', 'like', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->first();

        $sequence = 1;
        if ($lastCustomer && preg_match('/CUST-\d{4}-(\d+)/', $lastCustomer->customer_code, $matches)) {
            $sequence = intval($matches[1]) + 1;
        }

        return sprintf('%s%05d', $prefix, $sequence);
    }

    /**
     * Generate unique sequential Quotation Code: QT-YYYY-XXXXX
     */
    public static function generateQuotationCode(): string
    {
        $year = date('Y');
        $prefix = "QT-{$year}-";

        $lastQuotation = Quotation::withTrashed()
            ->where('quotation_code', 'like', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->first();

        $sequence = 1;
        if ($lastQuotation && preg_match('/QT-\d{4}-(\d+)/', $lastQuotation->quotation_code, $matches)) {
            $sequence = intval($matches[1]) + 1;
        }

        return sprintf('%s%05d', $prefix, $sequence);
    }

    /**
     * Check if a contact already exists in Leads or Customers
     * Returns array with duplicate status and matched record
     */
    public static function checkDuplicate(?string $phone, ?string $whatsapp = null, ?string $email = null, ?int $excludeLeadId = null): array
    {
        $normPhone = PhoneHelper::normalize($phone);
        $normWhatsapp = PhoneHelper::normalize($whatsapp);
        $cleanEmail = !empty($email) ? strtolower(trim($email)) : null;

        // 1. Check existing Customers first
        $customerQuery = Customer::query();
        $customerQuery->where(function ($q) use ($normPhone, $normWhatsapp, $cleanEmail) {
            if ($normPhone) {
                $q->orWhere('normalized_phone', $normPhone);
                $q->orWhere('normalized_whatsapp', $normPhone);
            }
            if ($normWhatsapp) {
                $q->orWhere('normalized_whatsapp', $normWhatsapp);
                $q->orWhere('normalized_phone', $normWhatsapp);
            }
            if ($cleanEmail) {
                $q->orWhere('email', $cleanEmail);
            }
        });

        $matchedCustomer = $customerQuery->first();
        if ($matchedCustomer) {
            return [
                'is_duplicate' => true,
                'type' => 'customer',
                'record' => $matchedCustomer,
                'message' => "An existing customer ({$matchedCustomer->customer_code}: {$matchedCustomer->name}) was found with this phone or email.",
            ];
        }

        // 2. Check existing Leads
        $leadQuery = Lead::query();
        if ($excludeLeadId) {
            $leadQuery->where('id', '!=', $excludeLeadId);
        }

        $leadQuery->where(function ($q) use ($normPhone, $normWhatsapp, $cleanEmail) {
            if ($normPhone) {
                $q->orWhere('normalized_phone', $normPhone);
                $q->orWhere('normalized_whatsapp', $normPhone);
            }
            if ($normWhatsapp) {
                $q->orWhere('normalized_whatsapp', $normWhatsapp);
                $q->orWhere('normalized_phone', $normWhatsapp);
            }
            if ($cleanEmail) {
                $q->orWhere('email', $cleanEmail);
            }
        });

        $matchedLead = $leadQuery->first();
        if ($matchedLead) {
            return [
                'is_duplicate' => true,
                'type' => 'lead',
                'record' => $matchedLead,
                'message' => "An existing lead ({$matchedLead->lead_code}: {$matchedLead->name}, Status: {$matchedLead->lead_status}) already exists with this phone or email.",
            ];
        }

        return [
            'is_duplicate' => false,
            'type' => null,
            'record' => null,
            'message' => null,
        ];
    }

    /**
     * Convert a Lead into a formal Customer
     */
    public static function convertToCustomer(Lead $lead, ?int $userId = null): Customer
    {
        return DB::transaction(function () use ($lead, $userId) {
            // Check if lead is already tied to a customer
            if ($lead->customer_id) {
                $customer = Customer::findOrFail($lead->customer_id);
            } else {
                // Check if customer with this normalized phone already exists
                $existingCustomer = Customer::where('normalized_phone', $lead->normalized_phone)
                    ->orWhere(function ($q) use ($lead) {
                        if ($lead->normalized_whatsapp) {
                            $q->where('normalized_whatsapp', $lead->normalized_whatsapp);
                        }
                    })->first();

                if ($existingCustomer) {
                    $customer = $existingCustomer;
                } else {
                    $customer = Customer::create([
                        'customer_code' => self::generateCustomerCode(),
                        'name' => $lead->name,
                        'company_name' => $lead->company_name,
                        'business_type' => $lead->business_type,
                        'email' => $lead->email,
                        'phone' => $lead->phone,
                        'normalized_phone' => $lead->normalized_phone,
                        'whatsapp' => $lead->whatsapp,
                        'normalized_whatsapp' => $lead->normalized_whatsapp,
                        'address' => $lead->address,
                        'city' => $lead->city,
                        'country' => $lead->country ?: 'Bangladesh',
                        'website' => $lead->website,
                        'facebook' => $lead->facebook,
                        'instagram' => $lead->instagram,
                        'status' => 'active',
                        'created_by' => $userId ?? auth()->id(),
                        'notes' => "Converted from Lead {$lead->lead_code}.",
                    ]);
                }

                // Link lead to this customer
                $lead->customer_id = $customer->id;
            }

            // Update lead status to Won if not already
            $lead->lead_status = 'Won';
            $lead->save();

            // Link existing follow-ups and quotations to customer
            $lead->followUps()->update(['customer_id' => $customer->id]);
            $lead->quotations()->update(['customer_id' => $customer->id]);

            // Log activity
            ActivityLog::log(
                action: 'converted_to_customer',
                description: "Lead {$lead->lead_code} successfully converted to customer {$customer->customer_code}.",
                subject: $customer,
                userId: $userId ?? auth()->id(),
                properties: ['lead_id' => $lead->id, 'lead_code' => $lead->lead_code]
            );

            return $customer;
        });
    }
}
