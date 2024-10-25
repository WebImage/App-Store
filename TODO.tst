
Cart
    Cart // Needs discount manager
    CartItem / CartSku // All cart item types inherit
    ?CartItemFactory
Categories
    Category
    CategoryItemFactory
Discount
    Discount
    DiscountManager
Meta
    MetaHelper
    ObjectMetaField
Orders
    OrderShipment
    OrderSkuShipment
    Order
    OrderSku
    OrderSkuFactory
Payments
    PaymentGateway: PaymentGatewayInterface
    PaymentInterfaceHelper
    PaymentManager
    PaymentOption: PaymentOptionInterface
Products
    Product
Shipping
    Shipment: ShipmentInterface
    ShipmentDelivery: ShipmentDeliveryInterface
    ShipmentPackageItem: ShipmentPackageItemInterface
    ShippingGatewayInterface: getRates($shipping_method_id, $package, &$message)
    ShippingManager
    ShippingMethodIdentifier
    ShippingOption
    ShippingPackageOption
Skus
    Sku
    SkuBase
