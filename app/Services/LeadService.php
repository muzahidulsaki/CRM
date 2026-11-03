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
     * Generate unique sequential Lead Code: PS10001 (matching CRM Format.xlsx)
     */
    public static function generateLeadCode(): string
    {
        $prefix = "PS";

        $lastLead = Lead::withTrashed()
            ->where('lead_code', 'like', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->first();

        $sequence = 10001;
        if ($lastLead && preg_match('/PS(\d+)/', $lastLead->lead_code, $matches)) {
            $sequence = max(10001, intval($matches[1]) + 1);
        }

        return sprintf('%s%d', $prefix, $sequence);
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
     * Helper to find existing record by a single contact field value (Customer or Lead)
     */
    protected static function findContactMatch(string $field, ?string $value, ?int $excludeLeadId = null): ?array
    {
        if (empty($value)) {
            return null;
        }

        $trimmed = trim($value);
        if (strlen($trimmed) < 4) {
            return null;
        }

        $norm = PhoneHelper::normalize($trimmed);
        $cleanEmail = ($field === 'email') ? strtolower($trimmed) : null;

        // 1. Check existing Customers
        $custQuery = Customer::query();
        if ($field === 'email') {
            if ($cleanEmail) {
                $custQuery->where('email', $cleanEmail);
            } else {
                return null;
            }
        } else {
            $custQuery->where(function ($q) use ($norm, $trimmed) {
                if ($norm) {
                    $q->where('normalized_phone', $norm)
                      ->orWhere('normalized_whatsapp', $norm);
                }
                $q->orWhere('phone', $trimmed)
                  ->orWhere('whatsapp', $trimmed);
            });
        }

        $matchedCustomer = $custQuery->first();
        if ($matchedCustomer) {
            return [
                'type' => 'customer',
                'record' => $matchedCustomer,
                'code' => $matchedCustomer->customer_code,
                'name' => $matchedCustomer->name,
                'desc' => "Customer {$matchedCustomer->customer_code}: {$matchedCustomer->name}",
            ];
        }

        // 2. Check existing Leads
        $leadQuery = Lead::query();
        if ($excludeLeadId) {
            $leadQuery->where('id', '!=', $excludeLeadId);
        }

        if ($field === 'email') {
            $leadQuery->where('email', $cleanEmail);
        } else {
            $leadQuery->where(function ($q) use ($norm, $trimmed) {
                if ($norm) {
                    $q->where('normalized_phone', $norm)
                      ->orWhere('normalized_whatsapp', $norm);
                }
                $q->orWhere('phone', $trimmed)
                  ->orWhere('whatsapp', $trimmed)
                  ->orWhere('imo_number', $trimmed);
            });
        }

        $matchedLead = $leadQuery->first();
        if ($matchedLead) {
            return [
                'type' => 'lead',
                'record' => $matchedLead,
                'code' => $leadQuery->where('id', $matchedLead->id)->value('lead_code') ?? $matchedLead->lead_code,
                'name' => $matchedLead->name,
                'desc' => "Lead {$matchedLead->lead_code}: {$matchedLead->name}",
            ];
        }

        return null;
    }

    /**
     * Check if a contact already exists in Leads or Customers
     * Returns array with duplicate status, matched record, and field-specific errors
     */
    public static function checkDuplicate(?string $phone, ?string $whatsapp = null, ?string $email = null, ?int $excludeLeadId = null, ?string $imo = null): array
    {
        $fieldErrors = [];
        $firstMatch = null;

        if (!empty($phone)) {
            $match = self::findContactMatch('phone', $phone, $excludeLeadId);
            if ($match) {
                $fieldErrors['phone'] = "This phone number is already used in {$match['desc']}!";
                $firstMatch = $firstMatch ?? $match;
            }
        }

        if (!empty($whatsapp)) {
            $match = self::findContactMatch('whatsapp', $whatsapp, $excludeLeadId);
            if ($match) {
                $fieldErrors['whatsapp'] = "This WhatsApp number is already used in {$match['desc']}!";
                $firstMatch = $firstMatch ?? $match;
            }
        }

        if (!empty($imo)) {
            $match = self::findContactMatch('imo_number', $imo, $excludeLeadId);
            if ($match) {
                $fieldErrors['imo_number'] = "This Imo number is already used in {$match['desc']}!";
                $firstMatch = $firstMatch ?? $match;
            }
        }

        if (!empty($email)) {
            $match = self::findContactMatch('email', $email, $excludeLeadId);
            if ($match) {
                $fieldErrors['email'] = "This email address is already used in {$match['desc']}!";
                $firstMatch = $firstMatch ?? $match;
            }
        }

        $isDuplicate = !empty($fieldErrors);

        if ($isDuplicate && $firstMatch) {
            $type = $firstMatch['type'];
            $record = $firstMatch['record'];
            $message = "An existing {$type} ({$firstMatch['code']}: {$firstMatch['name']}) already exists with these contact details.";
        } else {
            $type = null;
            $record = null;
            $message = null;
        }

        return [
            'is_duplicate' => $isDuplicate,
            'type' => $type,
            'record' => $record,
            'message' => $message,
            'field_errors' => $fieldErrors,
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
