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
 * @property string|null $code
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Curriculum newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Curriculum newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Curriculum onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Curriculum query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Curriculum whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Curriculum whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Curriculum whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Curriculum whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Curriculum whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Curriculum whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Curriculum withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Curriculum withoutTrashed()
 */
	class Curriculum extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $representative
 * @property string|null $address
 * @property string|null $phone
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereRepresentative($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer withoutTrashed()
 */
	class Customer extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $order_id
 * @property string $document_number
 * @property string $entry_date
 * @property string $note
 * @property int|null $sort
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DeliveryOrderProduct> $deliveryOrderProducts
 * @property-read int|null $delivery_order_products_count
 * @property-read \App\Models\Order|null $order
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryOrder whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryOrder whereDocumentNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryOrder whereEntryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryOrder whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryOrder whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryOrder whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryOrder whereUpdatedAt($value)
 */
	class DeliveryOrder extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $delivery_order_id
 * @property int $order_product_id
 * @property int $quantity
 * @property int|null $sort
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\DeliveryOrder|null $deliveryOrder
 * @property-read \App\Models\OrderProduct|null $orderProduct
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryOrderProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryOrderProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryOrderProduct onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryOrderProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryOrderProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryOrderProduct whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryOrderProduct whereDeliveryOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryOrderProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryOrderProduct whereOrderProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryOrderProduct whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryOrderProduct whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryOrderProduct whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryOrderProduct withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryOrderProduct withoutTrashed()
 */
	class DeliveryOrderProduct extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string|null $code
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationClass newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationClass newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationClass onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationClass query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationClass whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationClass whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationClass whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationClass whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationClass whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationClass whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationClass withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationClass withoutTrashed()
 */
	class EducationClass extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string|null $code
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationLevel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationLevel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationLevel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationLevel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationLevel whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationLevel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationLevel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationLevel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationLevel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationLevel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationLevel withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationLevel withoutTrashed()
 */
	class EducationLevel extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string|null $code
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationSubject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationSubject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationSubject onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationSubject query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationSubject whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationSubject whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationSubject whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationSubject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationSubject whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationSubject whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationSubject withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationSubject withoutTrashed()
 */
	class EducationSubject extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $order_id
 * @property string $document_number
 * @property string $entry_date
 * @property string $due_date
 * @property float $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\InvoiceReport> $invoiceReports
 * @property-read int|null $invoice_reports_count
 * @property-read \App\Models\Order $order
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderProductInvoice> $orderProductInvoices
 * @property-read int|null $order_product_invoices_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereDocumentNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereEntryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereUpdatedAt($value)
 */
	class Invoice extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $customer_id
 * @property string $document_number
 * @property string $entry_date
 * @property string $start_date
 * @property string $end_date
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Customer $customer
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Invoice> $invoices
 * @property-read int|null $invoices_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceReport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceReport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceReport query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceReport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceReport whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceReport whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceReport whereDocumentNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceReport whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceReport whereEntryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceReport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceReport whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceReport whereUpdatedAt($value)
 */
	class InvoiceReport extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $paper_config
 * @property int|null $sort
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Machine newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Machine newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Machine onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Machine query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Machine whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Machine whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Machine whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Machine whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Machine whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Machine wherePaperConfig($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Machine whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Machine whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Machine withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Machine withoutTrashed()
 */
	class Machine extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $description
 * @property string $unit
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $prices
 * @property-read mixed $stocks
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MaterialPurchase> $purchases
 * @property-read int|null $purchases_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MaterialUsage> $usages
 * @property-read int|null $usages_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material withoutTrashed()
 */
	class Material extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $material_supplier_id
 * @property string $proof_number
 * @property string $purchase_date
 * @property int $is_paid
 * @property string|null $paid_off_date
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MaterialPurchaseItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\MaterialSupplier $materialSupplier
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialPurchase newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialPurchase newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialPurchase onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialPurchase query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialPurchase whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialPurchase whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialPurchase whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialPurchase whereIsPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialPurchase whereMaterialSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialPurchase whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialPurchase wherePaidOffDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialPurchase whereProofNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialPurchase wherePurchaseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialPurchase whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialPurchase withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialPurchase withoutTrashed()
 */
	class MaterialPurchase extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $material_purchase_id
 * @property int $material_id
 * @property int $quantity
 * @property int $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $total
 * @property-read \App\Models\Material $material
 * @property-read \App\Models\MaterialPurchase $materialPurchase
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialPurchaseItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialPurchaseItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialPurchaseItem onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialPurchaseItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialPurchaseItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialPurchaseItem whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialPurchaseItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialPurchaseItem whereMaterialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialPurchaseItem whereMaterialPurchaseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialPurchaseItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialPurchaseItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialPurchaseItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialPurchaseItem withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialPurchaseItem withoutTrashed()
 */
	class MaterialPurchaseItem extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string|null $code
 * @property string $name
 * @property string|null $address
 * @property string|null $phone
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MaterialPurchase> $purchases
 * @property-read int|null $purchases_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialSupplier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialSupplier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialSupplier query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialSupplier whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialSupplier whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialSupplier whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialSupplier whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialSupplier whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialSupplier whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialSupplier wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialSupplier whereUpdatedAt($value)
 */
	class MaterialSupplier extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $usage_date
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MaterialUsageItem> $items
 * @property-read int|null $items_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialUsage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialUsage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialUsage onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialUsage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialUsage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialUsage whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialUsage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialUsage whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialUsage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialUsage whereUsageDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialUsage withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialUsage withoutTrashed()
 */
	class MaterialUsage extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $material_usage_id
 * @property int $material_id
 * @property int $machine_id
 * @property int $quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Machine $machine
 * @property-read \App\Models\Material $material
 * @property-read \App\Models\MaterialUsage $materialUsage
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialUsageItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialUsageItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialUsageItem onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialUsageItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialUsageItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialUsageItem whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialUsageItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialUsageItem whereMachineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialUsageItem whereMaterialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialUsageItem whereMaterialUsageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialUsageItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialUsageItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialUsageItem withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialUsageItem withoutTrashed()
 */
	class MaterialUsageItem extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $document_number
 * @property string $proof_number
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $entry_date
 * @property \Illuminate\Support\Carbon|null $deadline_date
 * @property int $paper_config
 * @property string $finished_size
 * @property string $material_size
 * @property int $customer_id
 * @property int $paper_id
 * @property int|null $sort
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Customer|null $customer
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DeliveryOrder> $deliveryOrders
 * @property-read int|null $delivery_orders_count
 * @property-read mixed $is_printed
 * @property-read mixed $status
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Invoice> $invoices
 * @property-read int|null $invoices_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderProduct> $orderProducts
 * @property-read int|null $order_products_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderProduct> $order_products
 * @property-read \App\Models\Paper|null $paper
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SpkProduct> $spkProducts
 * @property-read int|null $spk_products_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Spk> $spks
 * @property-read int|null $spks_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDeadlineDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDocumentNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereEntryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereFinishedSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereMaterialSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePaperConfig($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePaperId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereProofNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order withoutTrashed()
 */
	class Order extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property int $quantity
 * @property int|null $sort
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DeliveryOrderProduct> $deliveryOrderProducts
 * @property-read int|null $delivery_order_products_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DeliveryOrder> $deliveryOrders
 * @property-read int|null $delivery_orders_count
 * @property-read mixed $raw_result
 * @property-read mixed $result
 * @property-read mixed $status
 * @property-read \App\Models\Order|null $order
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderProductInvoice> $orderProductInvoices
 * @property-read int|null $order_product_invoices_count
 * @property-read \App\Models\Product|null $product
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SpkProduct> $spkOrderProdutcs
 * @property-read int|null $spk_order_produtcs_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProduct onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProduct whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProduct whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProduct whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProduct whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProduct whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProduct withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProduct withoutTrashed()
 */
	class OrderProduct extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $order_product_id
 * @property int $invoice_id
 * @property int $quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Models\Invoice $invoice
 * @property-read \App\Models\OrderProduct $orderProduct
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProductInvoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProductInvoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProductInvoice query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProductInvoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProductInvoice whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProductInvoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProductInvoice whereInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProductInvoice whereOrderProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProductInvoice whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProductInvoice whereUpdatedAt($value)
 */
	class OrderProductInvoice extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $code
 * @property int|null $sort
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paper newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paper newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paper onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paper query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paper whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paper whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paper whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paper whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paper whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paper whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paper whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paper withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paper withoutTrashed()
 */
	class Paper extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaperUsage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaperUsage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaperUsage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaperUsage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaperUsage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaperUsage whereUpdatedAt($value)
 */
	class PaperUsage extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $curriculum_id
 * @property int $semester_id
 * @property int $education_level_id
 * @property int $education_class_id
 * @property int $education_subject_id
 * @property int $type_id
 * @property int|null $sort
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Curriculum|null $curriculum
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DeliveryOrderProduct> $deliveryOrderProducts
 * @property-read int|null $delivery_order_products_count
 * @property-read \App\Models\EducationClass|null $educationClass
 * @property-read \App\Models\EducationLevel|null $educationLevel
 * @property-read \App\Models\EducationSubject|null $educationSubject
 * @property-read mixed $code
 * @property-read mixed $short_name
 * @property-read \App\Models\Order|null $order
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderProduct> $orderProducts
 * @property-read int|null $order_products_count
 * @property-read \App\Models\Semester|null $semester
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SpkProduct> $spkProducts
 * @property-read int|null $spk_products_count
 * @property-read \App\Models\Type|null $type
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCurriculumId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereEducationClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereEducationLevelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereEducationSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereSemesterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product withoutTrashed()
 */
	class Product extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $spk_id
 * @property int $spk_order_product_id
 * @property int $machine_id
 * @property string $date
 * @property string $start_time
 * @property string $end_time
 * @property int $success_count
 * @property int $error_count
 * @property int $status
 * @property int|null $sort
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read mixed $duration
 * @property-read \App\Models\Machine|null $machine
 * @property-read \App\Models\Spk|null $spk
 * @property-read \App\Models\SpkProduct|null $spkProduct
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductReport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductReport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductReport query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductReport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductReport whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductReport whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductReport whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductReport whereErrorCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductReport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductReport whereMachineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductReport whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductReport whereSpkId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductReport whereSpkOrderProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductReport whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductReport whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductReport whereSuccessCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductReport whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductReport withDuration()
 */
	class ProductReport extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $semester
 * @property string $start_date
 * @property string $end_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester whereSemester($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester withoutTrashed()
 */
	class Semester extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $order_id
 * @property string $document_number
 * @property string $report_number
 * @property \Illuminate\Support\Carbon $entry_date
 * @property \Illuminate\Support\Carbon $deadline_date
 * @property int $paper_config
 * @property string $configuration
 * @property string $note
 * @property string $print_type
 * @property int $spare
 * @property int|null $sort
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $error
 * @property-read mixed $is_printed
 * @property-read mixed $progress
 * @property-read mixed $result
 * @property-read \App\Models\Machine|null $machine
 * @property-read \App\Models\Order|null $order
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductReport> $productReports
 * @property-read int|null $product_reports_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SpkProduct> $spkProducts
 * @property-read int|null $spk_products_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Spk newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Spk newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Spk onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Spk query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Spk whereConfiguration($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Spk whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Spk whereDeadlineDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Spk whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Spk whereDocumentNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Spk whereEntryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Spk whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Spk whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Spk whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Spk wherePaperConfig($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Spk wherePrintType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Spk whereReportNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Spk whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Spk whereSpare($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Spk whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Spk withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Spk withoutTrashed()
 */
	class Spk extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $spk_id
 * @property array<array-key, mixed> $order_products
 * @property int|null $sort
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $products
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderProduct> $orderProducts
 * @property-read int|null $order_products_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductReport> $productReports
 * @property-read int|null $product_reports_count
 * @property-read \App\Models\Spk|null $spk
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpkProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpkProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpkProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpkProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpkProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpkProduct whereOrderProducts($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpkProduct whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpkProduct whereSpkId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpkProduct whereUpdatedAt($value)
 */
	class SpkProduct extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property int|null $sort
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Type newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Type newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Type onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Type query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Type whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Type whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Type whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Type whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Type whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Type whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Type whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Type withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Type withoutTrashed()
 */
	class Type extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $username
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $avatar_url
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $breezy_session
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Jeffgreco13\FilamentBreezy\Models\BreezySession> $breezySessions
 * @property-read int|null $breezy_sessions_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read mixed $two_factor_recovery_codes
 * @property-read mixed $two_factor_secret
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAvatarUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 */
	class User extends \Eloquent implements \Filament\Models\Contracts\FilamentUser, \Filament\Models\Contracts\HasAvatar {}
}

