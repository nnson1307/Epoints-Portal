<?php

namespace Modules\Admin\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Admin\Repositories\Action\ActionRepository;
use Modules\Admin\Repositories\Action\ActionRepositoryInterface;
use Modules\Admin\Repositories\AppointmentService\AppointmentServiceRepository;
use Modules\Admin\Repositories\AppointmentService\AppointmentServiceRepositoryInterface;
use Modules\Admin\Repositories\AppointmentSource\AppointmentSourceRepository;
use Modules\Admin\Repositories\AppointmentSource\AppointmentSourceRepositoryInterface;
use Modules\Admin\Repositories\BannerSlider\BannerSliderRepository;
use Modules\Admin\Repositories\BannerSlider\BannerSliderRepositoryInterface;
use Modules\Admin\Repositories\BookingExtra\BookingExtraRepository;
use Modules\Admin\Repositories\BookingExtra\BookingExtraRepositoryInterface;
use Modules\Admin\Repositories\Branch\BranchRepository;
use Modules\Admin\Repositories\Branch\BranchRepositoryInterface;
use Modules\Admin\Repositories\BranchImage\BranchImageRepository;
use Modules\Admin\Repositories\BranchImage\BranchImageRepositoryInterface;
use Modules\Admin\Repositories\Bussiness\BussinessRepository;
use Modules\Admin\Repositories\Bussiness\BussinessRepositoryInterface;
use Modules\Admin\Repositories\Calendar\CalendarRepo;
use Modules\Admin\Repositories\Calendar\CalendarRepoInterface;
use Modules\Admin\Repositories\CodeGenerator\CodeGeneratorRepository;
use Modules\Admin\Repositories\CodeGenerator\CodeGeneratorRepositoryInterface;
use Modules\Admin\Repositories\Collection\CollectionRepo;
use Modules\Admin\Repositories\Collection\CollectionRepoIf;
use Modules\Admin\Repositories\CommissionLog\CommissionLogRepository;
use Modules\Admin\Repositories\CommissionLog\CommissionLogRepositoryInterface;
use Modules\Admin\Repositories\Config\ConfigRepo;
use Modules\Admin\Repositories\Config\ConfigRepoInterface;
use Modules\Admin\Repositories\ConfigPrintBill\ConfigPrintBillRepository;
use Modules\Admin\Repositories\ConfigPrintBill\ConfigPrintBillRepositoryInterface;
use Modules\Admin\Repositories\ConfigPrintServiceCard\ConfigPrintServiceCardRepository;
use Modules\Admin\Repositories\ConfigPrintServiceCard\ConfigPrintServiceCardRepositoryInterface;
use Modules\Admin\Repositories\ConfigEmailTemplate\ConfigEmailTemplateRepository;
use Modules\Admin\Repositories\ConfigEmailTemplate\ConfigEmailTemplateRepositoryInterface;
use Modules\Admin\Repositories\ConfigTimeResetRank\ConfigTimeResetRankRepo;
use Modules\Admin\Repositories\ConfigTimeResetRank\ConfigTimeResetRankRepoInterface;
use Modules\Admin\Repositories\Customer\CustomerRepository;
use Modules\Admin\Repositories\Customer\CustomerRepositoryInterface;
use Modules\Admin\Repositories\CustomerAppointment\CustomerAppointmentRepository;
use Modules\Admin\Repositories\CustomerAppointment\CustomerAppointmentRepositoryInterface;
use Modules\Admin\Repositories\CustomerAppointmentDetail\CustomerAppointmentDetailRepository;
use Modules\Admin\Repositories\CustomerAppointmentDetail\CustomerAppointmentDetailRepositoryInterface;
use Modules\Admin\Repositories\CustomerAppointmentTime\CustomerAppointmentTimeRepository;
use Modules\Admin\Repositories\CustomerAppointmentTime\CustomerAppointmentTimeRepositoryInterface;
use Modules\Admin\Repositories\CustomerBranchMoney\CustomerBranchMoneyRepository;
use Modules\Admin\Repositories\CustomerBranchMoney\CustomerBranchMoneyRepositoryInterface;
use Modules\Admin\Repositories\CustomerDebt\CustomerDebtRepository;
use Modules\Admin\Repositories\CustomerDebt\CustomerDebtRepositoryInterface;
use Modules\Admin\Repositories\CustomerGroup\CustomerGroupRepository;
use Modules\Admin\Repositories\CustomerGroup\CustomerGroupRepositoryInterface;
use Modules\Admin\Repositories\CustomerGroupFilter\CustomerGroupFilterRepository;
use Modules\Admin\Repositories\CustomerGroupFilter\CustomerGroupFilterRepositoryInterface;
use Modules\Admin\Repositories\CustomerLog\CustomerLogRepo;
use Modules\Admin\Repositories\CustomerServiceCard\CustomerServiceCardRepository;
use Modules\Admin\Repositories\CustomerServiceCard\CustomerServiceCardRepositoryInterface;
use Modules\Admin\Repositories\CustomerSource\CustomerSourceRepository;
use Modules\Admin\Repositories\CustomerSource\CustomerSourceRepositoryInterface;
use Modules\Admin\Repositories\Department\DepartmentRepository;
use Modules\Admin\Repositories\Department\DepartmentRepositoryInterface;
use Modules\Admin\Repositories\EmailCampaign\EmailCampaignRepository;
use Modules\Admin\Repositories\EmailCampaign\EmailCampaignRepositoryInterface;
use Modules\Admin\Repositories\EmailCampaignDetail\EmailCampaignDetailRepository;
use Modules\Admin\Repositories\EmailCampaignDetail\EmailCampaignDetailRepositoryInterface;
use Modules\Admin\Repositories\EmailConfig\EmailConfigRepository;
use Modules\Admin\Repositories\EmailConfig\EmailConfigRepositoryInterface;
use Modules\Admin\Repositories\EmailLog\EmailLogRepository;
use Modules\Admin\Repositories\EmailLog\EmailLogRepositoryInterface;
use Modules\Admin\Repositories\EmailProvider\EmailProviderRepository;
use Modules\Admin\Repositories\EmailProvider\EmailProviderRepositoryInterface;
use Modules\Admin\Repositories\EmailTemplate\EmailTemplateRepository;
use Modules\Admin\Repositories\EmailTemplate\EmailTemplateRepositoryInterface;
use Modules\Admin\Repositories\ExportData\ExportDataRepo;
use Modules\Admin\Repositories\ExportData\ExportDataRepoInterface;
use Modules\Admin\Repositories\Faq\FaqRepository;
use Modules\Admin\Repositories\Faq\FaqRepositoryInterface;
use Modules\Admin\Repositories\FaqGroup\FaqGroupRepository;
use Modules\Admin\Repositories\FaqGroup\FaqGroupRepositoryInterface;
use Modules\Admin\Repositories\InventoryInput\InventoryInputRepository;
use Modules\Admin\Repositories\InventoryInput\InventoryInputRepositoryInterface;
use Modules\Admin\Repositories\Log\LogRepository;
use Modules\Admin\Repositories\Log\LogRepositoryInterface;
use Modules\Admin\Repositories\Loyalty\LoyaltyRepository;
use Modules\Admin\Repositories\Loyalty\LoyaltyRepositoryInterface;
use Modules\Admin\Repositories\MapProductAttribute\MapProductAttributeRepository;
use Modules\Admin\Repositories\MapProductAttribute\MapProductAttributeRepositoryInterface;
use Modules\Admin\Repositories\MapRoleGroupStaff\MapRoleGroupStaffRepository;
use Modules\Admin\Repositories\MapRoleGroupStaff\MapRoleGroupStaffRepositoryInterface;
use Modules\Admin\Repositories\MemberLevel\MemberLevelRepository;
use Modules\Admin\Repositories\MemberLevel\MemberLevelRepositoryInterface;
use Modules\Admin\Repositories\MemberLevelVerb\MemberLevelVerbRepository;
use Modules\Admin\Repositories\MemberLevelVerb\MemberLevelVerbRepositoryInterface;
use Modules\Admin\Repositories\MenuAll\MenuAllRepo;
use Modules\Admin\Repositories\MenuAll\MenuAllRepoInterface;
use Modules\Admin\Repositories\MenuHorizontal\MenuHorizontalRepo;
use Modules\Admin\Repositories\MenuHorizontal\MenuHorizontalRepoInterface;
use Modules\Admin\Repositories\MenuVertical\MenuVerticalRepo;
use Modules\Admin\Repositories\MenuVertical\MenuVerticalRepoInterface;
use Modules\Admin\Repositories\News\NewRepo;
use Modules\Admin\Repositories\News\NewRepoInterface;
use Modules\Admin\Repositories\Notification\NotificationRepo;
use Modules\Admin\Repositories\Notification\NotificationRepoInterface;
use Modules\Admin\Repositories\Order\OrderRepository;
use Modules\Admin\Repositories\Order\OrderRepositoryInterface;
use Modules\Admin\Repositories\OrderApp\OrderAppRepo;
use Modules\Admin\Repositories\OrderApp\OrderAppRepoInterface;
use Modules\Admin\Repositories\OrderCommission\OrderCommissionRepository;
use Modules\Admin\Repositories\OrderCommission\OrderCommissionRepositoryInterface;
use Modules\Admin\Repositories\OrderDeliveryStatus\OrderDeliveryStatusRepository;
use Modules\Admin\Repositories\OrderDeliveryStatus\OrderDeliveryStatusRepositoryInterface;
use Modules\Admin\Repositories\OrderDeliveryType\OrderDeliveryTypeRepository;
use Modules\Admin\Repositories\OrderDeliveryType\OrderDeliveryTypeRepositoryInterface;
use Modules\Admin\Repositories\OrderDetail\OrderDetailRepository;
use Modules\Admin\Repositories\OrderDetail\OrderDetailRepositoryInterface;
use Modules\Admin\Repositories\OrderPaymentType\OrderPaymentTypeRepository;
use Modules\Admin\Repositories\OrderPaymentType\OrderPaymentTypeRepositoryInterface;
use Modules\Admin\Repositories\OrderReasonCancel\OrderReasonCancelRepository;
use Modules\Admin\Repositories\OrderReasonCancel\OrderReasonCancelRepositoryInterface;
use Modules\Admin\Repositories\OrderSource\OrderSourceRepository;
use Modules\Admin\Repositories\OrderSource\OrderSourceRepositoryInterface;
use Modules\Admin\Repositories\OrderStatus\OrderStatusRepository;
use Modules\Admin\Repositories\OrderStatus\OrderStatusRepositoryInterface;
use Modules\Admin\Repositories\Page\PageRepository;
use Modules\Admin\Repositories\Page\PageRepositoryInterface;
use Modules\Admin\Repositories\PointHistory\PointHistoryRepo;
use Modules\Admin\Repositories\PointHistory\PointHistoryRepoInterface;
use Modules\Admin\Repositories\PointRewardRule\PointRewardRuleRepository;
use Modules\Admin\Repositories\PointRewardRule\PointRewardRuleRepositoryInterface;
use Modules\Admin\Repositories\PrintBillLog\PrintBillLogRepository;
use Modules\Admin\Repositories\PrintBillLog\PrintBillLogRepositoryInterface;
use Modules\Admin\Repositories\Product\ProductRepository;
use Modules\Admin\Repositories\Product\ProductRepositoryInterface;
use Modules\Admin\Repositories\ProductAttribute\ProductAttributeRepository;
use Modules\Admin\Repositories\ProductAttribute\ProductAttributeRepositoryInterface;
use Modules\Admin\Repositories\ProductAttributeGroup\ProductAttributeGroupRepository;
use Modules\Admin\Repositories\ProductAttributeGroup\ProductAttributeGroupRepositoryInterface;
use Modules\Admin\Repositories\ProductBranchPrice\ProductBranchPriceRepository;
use Modules\Admin\Repositories\ProductBranchPrice\ProductBranchPriceRepositoryInterface;
use Modules\Admin\Repositories\ProductCategory\ProductCategoryRepository;
use Modules\Admin\Repositories\ProductCategory\ProductCategoryRepositoryInterface;
use Modules\Admin\Repositories\ProductCategoryParent\ProductCategoryParentRepo;
use Modules\Admin\Repositories\ProductCategoryParent\ProductCategoryParentRepoIf;
use Modules\Admin\Repositories\ProductChild\ProductChildRepository;
use Modules\Admin\Repositories\ProductChild\ProductChildRepositoryInterface;
use Modules\Admin\Repositories\ProductChildNew\ProductChildNewRepository;
use Modules\Admin\Repositories\ProductChildNew\ProductChildNewRepositoryInterface;
use Modules\Admin\Repositories\ProductConfig\ProductConfigRepo;
use Modules\Admin\Repositories\ProductConfig\ProductConfigRepoInterface;
use Modules\Admin\Repositories\ProductGroup\ProductGroupRepository;
use Modules\Admin\Repositories\ProductGroup\ProductGroupRepositoryInterface;
use Modules\Admin\Repositories\ProductImage\ProductImageRepository;
use Modules\Admin\Repositories\ProductImage\ProductImageRepositoryInterface;
use Modules\Admin\Repositories\ProductLabel\ProductLabelRepository;
use Modules\Admin\Repositories\ProductLabel\ProductLabelRepositoryInterface;
use Modules\Admin\Repositories\ProductModel\ProductModelRepository;
use Modules\Admin\Repositories\ProductModel\ProductModelRepositoryInterface;
use Modules\Admin\Repositories\ProductOrigin\ProductOriginRepository;
use Modules\Admin\Repositories\ProductOrigin\ProductOriginRepositoryInterface;
use Modules\Admin\Repositories\ProductTag\ProductTagRepo;
use Modules\Admin\Repositories\ProductTag\ProductTagRepoInterface;
use Modules\Admin\Repositories\ProductUnit\ProductUnitRepository;
use Modules\Admin\Repositories\ProductUnit\ProductUnitRepositoryInterface;
use Modules\Admin\Repositories\Rating\RatingRepo;
use Modules\Admin\Repositories\Rating\RatingRepoInterface;
use Modules\Admin\Repositories\RatingOrder\RatingOrderRepo;
use Modules\Admin\Repositories\RatingOrder\RatingOrderRepoInterface;
use Modules\Admin\Repositories\Receipt\ReceiptRepository;
use Modules\Admin\Repositories\Receipt\ReceiptRepositoryInterface;
use Modules\Admin\Repositories\ReceiptDetail\ReceiptDetailRepository;
use Modules\Admin\Repositories\ReceiptDetail\ReceiptDetailRepositoryInterface;
use Modules\Admin\Repositories\ResetRankLog\ResetRankLogRepo;
use Modules\Admin\Repositories\ResetRankLog\ResetRankLogRepoInterface;
use Modules\Admin\Repositories\RoleAction\RoleActionRepository;
use Modules\Admin\Repositories\RoleAction\RoleActionRepositoryInterface;
use Modules\Admin\Repositories\RoleGroup\RoleGroupRepository;
use Modules\Admin\Repositories\RoleGroup\RoleGroupRepositoryInterface;
use Modules\Admin\Repositories\RolePage\RolePageRepository;
use Modules\Admin\Repositories\RolePage\RolePageRepositoryInterface;
use Modules\Admin\Repositories\Room\RoomRepository;
use Modules\Admin\Repositories\Room\RoomRepositoryInterface;
use Modules\Admin\Repositories\RuleBooking\RuleBookingRepository;
use Modules\Admin\Repositories\RuleBooking\RuleBookingRepositoryInterface;
use Modules\Admin\Repositories\RuleMenu\RuleMenuRepository;
use Modules\Admin\Repositories\RuleMenu\RuleMenuRepositoryInterface;
use Modules\Admin\Repositories\RuleSettingOther\RuleSettingOtherRepository;
use Modules\Admin\Repositories\RuleSettingOther\RuleSettingOtherRepositoryInterface;
use Modules\Admin\Repositories\ServiceBooking\ServiceBookingRepo;
use Modules\Admin\Repositories\ServiceBooking\ServiceBookingRepoInterface;
use Modules\Admin\Repositories\ServiceBranchPrice\ServiceBranchPriceRepository;
use Modules\Admin\Repositories\ServiceBranchPrice\ServiceBranchPriceRepositoryInterface;
use Modules\Admin\Repositories\ServiceCard\ServiceCardRepository;
use Modules\Admin\Repositories\ServiceCard\ServiceCardRepositoryInterface;
use Modules\Admin\Repositories\ServiceCardGroup\ServiceCardGroupRepository;
use Modules\Admin\Repositories\ServiceCardGroup\ServiceCardGroupRepositoryInterface;
use Modules\Admin\Repositories\Service\ServiceRepository;
use Modules\Admin\Repositories\Service\ServiceRepositoryInterface;

use Modules\Admin\Repositories\ServiceCardList\ServiceCardListRepository;
use Modules\Admin\Repositories\ServiceCardList\ServiceCardListRepositoryInterface;
use Modules\Admin\Repositories\ServiceCategory\ServiceCategoryRepository;
use Modules\Admin\Repositories\ServiceCategory\ServiceCategoryRepositoryInterface;
use Modules\Admin\Repositories\ServiceGroup\ServiceGroupRepository;
use Modules\Admin\Repositories\ServiceGroup\ServiceGroupRepositoryInterface;
use Modules\Admin\Repositories\ServiceImage\ServiceImageRepository;
use Modules\Admin\Repositories\ServiceImage\ServiceImageRepositoryInterface;
use Modules\Admin\Repositories\ServiceMaterial\ServiceMaterialRepository;
use Modules\Admin\Repositories\ServiceMaterial\ServiceMaterialRepositoryInterface;
use Modules\Admin\Repositories\ServicePackage\ServicePackageRepository;
use Modules\Admin\Repositories\ServicePackage\ServicePackageRepositoryInterface;
use Modules\Admin\Repositories\ServiceType\ServiceTypeRepository;
use Modules\Admin\Repositories\ServiceType\ServiceTypeRepositoryInterface;
use Modules\Admin\Repositories\Shift\ShiftRepository;
use Modules\Admin\Repositories\Shift\ShiftRepositoryInterface;
use Modules\Admin\Repositories\SpaInfo\SpaInfoRepository;
use Modules\Admin\Repositories\SpaInfo\SpaInfoRepositoryInterface;
use Modules\Admin\Repositories\StaffCommission\StaffCommissionRepo;
use Modules\Admin\Repositories\StaffCommission\StaffCommissionRepoInterface;
use Modules\Admin\Repositories\Staffs\StaffRepository;
use Modules\Admin\Repositories\Staffs\StaffRepositoryInterface;
use Modules\Admin\Repositories\StaffTitle\StaffTitleRepository;
use Modules\Admin\Repositories\StaffTitle\StaffTitleRepositoryInterface;
use Modules\Admin\Repositories\Store\StoreRepository;
use Modules\Admin\Repositories\Store\StoreRepositoryInterface;
use Modules\Admin\Repositories\Supplier\SupplierRepository;
use Modules\Admin\Repositories\Supplier\SupplierRepositoryInterface;
use Modules\Admin\Repositories\Tax\TaxRepository;
use Modules\Admin\Repositories\Tax\TaxRepositoryInterface;
use Modules\Admin\Repositories\TimeWorking\TimeWorkingRepository;
use Modules\Admin\Repositories\TimeWorking\TimeWorkingRepositoryInterface;
use Modules\Admin\Repositories\Transport\TransportRepository;
use Modules\Admin\Repositories\Transport\TransportRepositoryInterface;
use Modules\Admin\Repositories\Unit\UnitRepository;
use Modules\Admin\Repositories\Unit\UnitRepositoryInterface;
use Modules\Admin\Repositories\UnitConversion\UnitConversionRepository;
use Modules\Admin\Repositories\UnitConversion\UnitConversionRepositoryInterface;
use Modules\Admin\Repositories\Upload\UploadRepo;
use Modules\Admin\Repositories\Upload\UploadRepoInterface;
use Modules\Admin\Repositories\UploadImage\UploadImageRepository;
use Modules\Admin\Repositories\UploadImage\UploadImageRepositoryInterface;
use Modules\Admin\Repositories\Voucher\VoucherRepository;
use Modules\Admin\Repositories\Voucher\VoucherRepositoryInterface;
use Modules\Admin\Repositories\Warehouse\WarehouseRepository;
use Modules\Admin\Repositories\Warehouse\WarehouseRepositoryInterface;


use Modules\Admin\Repositories\InventoryInputDetail\InventoryInputDetailRepository;
use Modules\Admin\Repositories\InventoryInputDetail\InventoryInputDetailRepositoryInterface;

use Modules\Admin\Repositories\ProductInventory\ProductInventoryRepositoryInterface;
use Modules\Admin\Repositories\ProductInventory\ProductInventoryRepository;
use Modules\Admin\Repositories\InventoryOutput\InventoryOutputRepository;
use Modules\Admin\Repositories\InventoryOutput\InventoryOutputRepositoryInterface;
use Modules\Admin\Repositories\InventoryOutputDetail\InventoryOutputDetailRepository;
use Modules\Admin\Repositories\InventoryOutputDetail\InventoryOutputDetailRepositoryInterface;
use Modules\Admin\Repositories\InventoryTransfer\InventoryTransferRepository;
use Modules\Admin\Repositories\InventoryTransfer\InventoryTransferRepositoryInterface;
use Modules\Admin\Repositories\InventoryTransferDetail\InventoryTransferDetailRepository;
use Modules\Admin\Repositories\InventoryTransferDetail\InventoryTransferDetailRepositoryInterface;
use Modules\Admin\Repositories\InventoryChecking\InventoryCheckingRepository;
use Modules\Admin\Repositories\InventoryChecking\InventoryCheckingRepositoryInterface;
use Modules\Admin\Repositories\InventoryCheckingDetail\InventoryCheckingDetailRepository;
use Modules\Admin\Repositories\InventoryCheckingDetail\InventoryCheckingDetailRepositoryInterface;
use Modules\Admin\Repositories\BrandName\BrandNameRepositoryInterFace;
use Modules\Admin\Repositories\BrandName\BrandNameRepository;
use Modules\Admin\Repositories\SmsCampaign\SmsCampaignRepositoryInterface;
use Modules\Admin\Repositories\SmsCampaign\SmsCampaignRepository;
use Modules\Admin\Repositories\SmsProvider\SmsProviderRepository;
use Modules\Admin\Repositories\SmsProvider\SmsProviderRepositoryInterface;
use Modules\Admin\Repositories\SmsConfig\SmsConfigRepositoryInterface;
use Modules\Admin\Repositories\SmsConfig\SmsConfigRepository;
use Modules\Admin\Repositories\SmsLog\SmsLogRepositoryInterface;
use Modules\Admin\Repositories\SmsLog\SmsLogRepository;
use Modules\Admin\Repositories\SendSms\SendSmsRepositoryInterface;
use Modules\Admin\Repositories\SendSms\SendSmsRepository;
use Modules\Admin\Repositories\CustomerLog\CustomerLogRepoInterface;

use Modules\Admin\Repositories\OrderAll\OrderAllRepositoryInterface;
use Modules\Admin\Repositories\OrderAll\OrderAllRepository;


class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /*
         *
         * General Repository*/
        $this->app->singleton(UploadImageRepositoryInterface::class, UploadImageRepository::class);
        $this->app->singleton(ExportDataRepoInterface::class, ExportDataRepo::class);
        $this->app->singleton(CodeGeneratorRepositoryInterface::class, CodeGeneratorRepository::class);

        // Khai báo cái repository ở đây
        // Khai báo cái repository ở đây
        $this->app->singleton(ServiceGroupRepositoryInterface::class, ServiceGroupRepository::class);
        $this->app->singleton(ServicePackageRepositoryInterface::class, ServicePackageRepository::class);
        $this->app->singleton(ServiceTypeRepositoryInterface::class, ServiceTypeRepository::class);
        $this->app->singleton(ServiceTypeRepositoryInterface::class, ServiceTypeRepository::class);
        $this->app->singleton(ProductUnitRepositoryInterface::class, ProductUnitRepository::class);
        $this->app->singleton(ProductUnitRepositoryInterface::class, ProductUnitRepository::class);
        $this->app->singleton(CustomerSourceRepositoryInterface::class, CustomerSourceRepository::class);
        $this->app->singleton(CustomerGroupRepositoryInterface::class, CustomerGroupRepository::class);
        $this->app->singleton(OrderPaymentTypeRepositoryInterface::class, OrderPaymentTypeRepository::class);
        $this->app->singleton(StaffRepositoryInterface::class, StaffRepository::class);

        //Son
        $this->app->singleton(ProductOriginRepositoryInterface::class, ProductOriginRepository::class);
        $this->app->singleton(StaffTitleRepositoryInterface::class, StaffTitleRepository::class);
        $this->app->singleton(BranchRepositoryInterface::class, BranchRepository::class);
        $this->app->singleton(WarehouseRepositoryInterface::class, WarehouseRepository::class);
        $this->app->singleton(UnitRepositoryInterface::class, UnitRepository::class);
        $this->app->singleton(UnitConversionRepositoryInterface::class, UnitConversionRepository::class);
        $this->app->singleton(TransportRepositoryInterface::class, TransportRepository::class);
        $this->app->singleton(ProductLabelRepositoryInterface::class, ProductLabelRepository::class);
        $this->app->singleton(OrderDeliveryTypeRepositoryInterface::class, OrderDeliveryTypeRepository::class);
        $this->app->singleton(OrderDeliveryTypeRepositoryInterface::class, OrderDeliveryTypeRepository::class);
        $this->app->singleton(RoomRepositoryInterface::class, RoomRepository::class);
        $this->app->singleton(StaffRepositoryInterface::class, StaffRepository::class);
        $this->app->singleton(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->singleton(ProductChildRepositoryInterface::class, ProductChildRepository::class);
        $this->app->singleton(ProductBranchPriceRepositoryInterface::class, ProductBranchPriceRepository::class);
        $this->app->singleton(ServiceRepositoryInterface::class, ServiceRepository::class);
        $this->app->singleton(ServiceCategoryRepositoryInterface::class, ServiceCategoryRepository::class);
        $this->app->singleton(ServiceBranchPriceRepositoryInterface::class, ServiceBranchPriceRepository::class);
        $this->app->singleton(ServiceMaterialRepositoryInterface::class, ServiceMaterialRepository::class);
        $this->app->singleton(MapProductAttributeRepositoryInterface::class, MapProductAttributeRepository::class);
        $this->app->singleton(CustomerRepositoryInterface::class, CustomerRepository::class);
        $this->app->singleton(ServiceImageRepositoryInterface::class, ServiceImageRepository::class);
        $this->app->singleton(CustomerAppointmentRepositoryInterface::class, CustomerAppointmentRepository::class);
        $this->app->singleton(AppointmentServiceRepositoryInterface::class, AppointmentServiceRepository::class);
        $this->app->singleton(ShiftRepositoryInterface::class, ShiftRepository::class);
        $this->app->singleton(OrderDeliveryStatusRepositoryInterface::class, OrderDeliveryStatusRepository::class);
        $this->app->singleton(MemberLevelRepositoryInterface::class, MemberLevelRepository::class);
        $this->app->singleton(ProductGroupRepositoryInterface::class, ProductGroupRepository::class);
        $this->app->singleton(OrderSourceRepositoryInterface::class, OrderSourceRepository::class);
        $this->app->singleton(OrderStatusRepositoryInterface::class, OrderStatusRepository::class);
        $this->app->singleton(ProductLabelRepositoryInterface::class, ProductLabelRepository::class);
        $this->app->singleton(OrderReasonCancelRepositoryInterface::class, OrderReasonCancelRepository::class);
        $this->app->singleton(MemberLevelVerbRepositoryInterface::class, MemberLevelVerbRepository::class);
        $this->app->singleton(DepartmentRepositoryInterface::class, DepartmentRepository::class);
        $this->app->singleton(StoreRepositoryInterface::class, StoreRepository::class);
        $this->app->singleton(SupplierRepositoryInterface::class, SupplierRepository::class);
        $this->app->singleton(ProductInventoryRepositoryInterface::class, ProductInventoryRepository::class);
        $this->app->singleton(ProductCategoryRepositoryInterface::class, ProductCategoryRepository::class);
        $this->app->singleton(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->singleton(ProductModelRepositoryInterface::class, ProductModelRepository::class);
        $this->app->singleton(ProductAttributeGroupRepositoryInterface::class, ProductAttributeGroupRepository::class);
        $this->app->singleton(ProductAttributeRepositoryInterface::class, ProductAttributeRepository::class);
        $this->app->singleton(ProductImageRepositoryInterface::class, ProductImageRepository::class);
        $this->app->singleton(CustomerAppointmentTimeRepositoryInterface::class, CustomerAppointmentTimeRepository::class);
        $this->app->singleton(OrderDetailRepositoryInterface::class, OrderDetailRepository::class);
        $this->app->singleton(CustomerServiceCardRepositoryInterface::class, CustomerServiceCardRepository::class);
        $this->app->singleton(ReceiptRepositoryInterface::class, ReceiptRepository::class);
        $this->app->singleton(ReceiptDetailRepositoryInterface::class, ReceiptDetailRepository::class);
        $this->app->singleton(CustomerBranchMoneyRepositoryInterface::class, CustomerBranchMoneyRepository::class);
        $this->app->singleton(CustomerAppointmentDetailRepositoryInterface::class, CustomerAppointmentDetailRepository::class);
        $this->app->singleton(AppointmentSourceRepositoryInterface::class, AppointmentSourceRepository::class);
        $this->app->singleton(EmailCampaignRepositoryInterface::class, EmailCampaignRepository::class);
        $this->app->singleton(EmailCampaignDetailRepositoryInterface::class, EmailCampaignDetailRepository::class);
        $this->app->singleton(EmailProviderRepositoryInterface::class, EmailProviderRepository::class);
        $this->app->singleton(EmailConfigRepositoryInterface::class, EmailConfigRepository::class);
        $this->app->singleton(EmailLogRepositoryInterface::class, EmailLogRepository::class);
        $this->app->singleton(BranchImageRepositoryInterface::class, BranchImageRepository::class);
        $this->app->singleton(BussinessRepositoryInterface::class, BussinessRepository::class);
        $this->app->singleton(SpaInfoRepositoryInterface::class, SpaInfoRepository::class);
        $this->app->singleton(BannerSliderRepositoryInterface::class, BannerSliderRepository::class);
        $this->app->singleton(TimeWorkingRepositoryInterface::class, TimeWorkingRepository::class);
        $this->app->singleton(RuleMenuRepositoryInterface::class, RuleMenuRepository::class);
        $this->app->singleton(RuleBookingRepositoryInterface::class, RuleBookingRepository::class);
        $this->app->singleton(RuleSettingOtherRepositoryInterface::class, RuleSettingOtherRepository::class);
        $this->app->singleton(BookingExtraRepositoryInterface::class, BookingExtraRepository::class);
        $this->app->singleton(EmailTemplateRepositoryInterface::class, EmailTemplateRepository::class);
        $this->app->singleton(ConfigPrintServiceCardRepositoryInterface::class, ConfigPrintServiceCardRepository::class);
        $this->app->singleton(ConfigEmailTemplateRepositoryInterface::class, ConfigEmailTemplateRepository::class);
        $this->app->singleton(CustomerDebtRepositoryInterface::class, CustomerDebtRepository::class);
        $this->app->singleton(OrderCommissionRepositoryInterface::class, OrderCommissionRepository::class);
        $this->app->singleton(CommissionLogRepositoryInterface::class, CommissionLogRepository::class);
        $this->app->singleton(PointHistoryRepoInterface::class, PointHistoryRepo::class);
        $this->app->singleton(ConfigTimeResetRankRepoInterface::class, ConfigTimeResetRankRepo::class);
        $this->app->singleton(ConfigRepoInterface::class, ConfigRepo::class);
        $this->app->singleton(ResetRankLogRepoInterface::class, ResetRankLogRepo::class);
        $this->app->singleton(NotificationRepoInterface::class, NotificationRepo::class);
        $this->app->singleton(FaqGroupRepositoryInterface::class, FaqGroupRepository::class);
        $this->app->singleton(FaqRepositoryInterface::class, FaqRepository::class);
        $this->app->singleton(OrderAppRepoInterface::class, OrderAppRepo::class);
        $this->app->singleton(NewRepoInterface::class, NewRepo::class);
        $this->app->singleton(RatingRepoInterface::class, RatingRepo::class);
        /*
         *
         * Author:Quận, huyện, xã
         *
         * */
        $this->app->singleton(\Modules\Admin\Repositories\Province\ProvinceRepositoryInterface::class, \Modules\Admin\Repositories\Province\ProvinceRepository::class);
        $this->app->singleton(\Modules\Admin\Repositories\District\DistrictRepositoryInterface::class, \Modules\Admin\Repositories\District\DistrictRepository::class);
        $this->app->singleton(\Modules\Admin\Repositories\Ward\WardRepositoryInterface::class, \Modules\Admin\Repositories\Ward\WardRepository::class);


        /*
         *
         * Author:Huy
         *
         * */
        $this->app->singleton(ServiceCardRepositoryInterface::class, ServiceCardRepository::class);
        $this->app->singleton(ServiceCardGroupRepositoryInterface::class, ServiceCardGroupRepository::class);
        $this->app->singleton(ServiceCardListRepositoryInterface::class, ServiceCardListRepository::class);
        $this->app->singleton(ServiceCardRepositoryInterface::class, ServiceCardRepository::class);
        $this->app->singleton(ServiceCardGroupRepositoryInterface::class, ServiceCardGroupRepository::class);
        $this->app->singleton(ServiceCardListRepositoryInterface::class, ServiceCardListRepository::class);
        $this->app->singleton(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->singleton(VoucherRepositoryInterface::class, VoucherRepository::class);
        /*
         * Lê Đăng Sinh.
         */
        $this->app->singleton(InventoryInputRepositoryInterface::class, InventoryInputRepository::class);
        $this->app->singleton(InventoryInputDetailRepositoryInterface::class, InventoryInputDetailRepository::class);
        $this->app->singleton(InventoryOutputRepositoryInterface::class, InventoryOutputRepository::class);
        $this->app->singleton(InventoryOutputDetailRepositoryInterface::class, InventoryOutputDetailRepository::class);
        $this->app->singleton(InventoryTransferRepositoryInterface::class, InventoryTransferRepository::class);
        $this->app->singleton(InventoryTransferRepositoryInterface::class, InventoryTransferRepository::class);
        $this->app->singleton(InventoryTransferDetailRepositoryInterface::class, InventoryTransferDetailRepository::class);
        $this->app->singleton(InventoryCheckingRepositoryInterface::class, InventoryCheckingRepository::class);
        $this->app->singleton(InventoryCheckingDetailRepositoryInterface::class, InventoryCheckingDetailRepository::class);
        $this->app->singleton(BrandNameRepositoryInterFace::class, BrandNameRepository::class);
        $this->app->singleton(SmsCampaignRepositoryInterFace::class, SmsCampaignRepository::class);

        /*
         * SMS
         */
        $this->app->singleton(SmsProviderRepositoryInterface::class, SmsProviderRepository::class);
        $this->app->singleton(SmsConfigRepositoryInterface::class, SmsConfigRepository::class);
        $this->app->singleton(SmsLogRepositoryInterface::class, SmsLogRepository::class);
        $this->app->singleton(SendSmsRepositoryInterface::class, SendSmsRepository::class);

        //PRINT BILL
        $this->app->singleton(ConfigPrintBillRepositoryInterface::class, ConfigPrintBillRepository::class);
        $this->app->singleton(PrintBillLogRepositoryInterface::class, PrintBillLogRepository::class);

        //PHÂN QUYỀN.
        $this->app->singleton(PageRepositoryInterface::class, PageRepository::class);
        $this->app->singleton(ActionRepositoryInterface::class, ActionRepository::class);
        $this->app->singleton(RolePageRepositoryInterface::class, RolePageRepository::class);
        $this->app->singleton(RoleActionRepositoryInterface::class, RoleActionRepository::class);
        $this->app->singleton(RoleGroupRepositoryInterface::class, RoleGroupRepository::class);
        $this->app->singleton(MapRoleGroupStaffRepositoryInterface::class, MapRoleGroupStaffRepository::class);

        //NHÓM KHÁCH HÀNG NHẬN THÔNG BÁO
        $this->app->singleton(CustomerGroupFilterRepositoryInterface::class, CustomerGroupFilterRepository::class);

        $this->app->singleton(PointRewardRuleRepositoryInterface::class, PointRewardRuleRepository::class);
        $this->app->singleton(LoyaltyRepositoryInterface::class, LoyaltyRepository::class);

//        Log
        $this->app->singleton(LogRepositoryInterface::class, LogRepository::class);

        $this->app->singleton(ProductChildNewRepositoryInterface::class, ProductChildNewRepository::class);

        // MENU
        $this->app->singleton(MenuHorizontalRepoInterface::class, MenuHorizontalRepo::class);
        $this->app->singleton(MenuVerticalRepoInterface::class, MenuVerticalRepo::class);
        $this->app->singleton(MenuAllRepoInterface::class, MenuAllRepo::class);

        //Upload Image
        $this->app->singleton(UploadRepoInterface::class, UploadRepo::class);

        $this->app->singleton(CalendarRepoInterface::class, CalendarRepo::class);
        // Hoa hồng nhân viên
        $this->app->singleton(StaffCommissionRepoInterface::class, StaffCommissionRepo::class);
        //Danh sách xe đã book
        $this->app->singleton(ServiceBookingRepoInterface::class, ServiceBookingRepo::class);

        $this->app->singleton(ProductTagRepoInterface::class, ProductTagRepo::class);
        $this->app->singleton(ProductConfigRepoInterface::class, ProductConfigRepo::class);
        $this->app->singleton(RatingOrderRepoInterface::class, RatingOrderRepo::class);



        $this->app->singleton(CustomerLogRepoInterface::class, CustomerLogRepo::class);
        $this->app->singleton(TaxRepositoryInterface::class, TaxRepository::class);

        $this->app->singleton(CollectionRepoIf::class, CollectionRepo::class);
        $this->app->singleton(ProductCategoryParentRepoIf::class, ProductCategoryParentRepo::class);


        //order-all
        $this->app->singleton(OrderAllRepositoryInterface::class, OrderAllRepository::class);

    }
}
//
