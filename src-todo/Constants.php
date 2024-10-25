<?php

namespace WebImage\Store;

class Constants
{
	const DISCOUNTREQUIREMENTS_NONE = 1;
	const DISCOUNTREQUIREMENTS_PURCHASEDALLSKUS = 2;
	const DISCOUNTREQUIREMENTS_PURCHASEDALLSKUSAFTERDATE = 3;
	const DISCOUNTREQUIREMENTS_PURCHASEDALLSKUSWITHINNDAYS = 4;
	const DISCOUNTREQUIREMENTS_PURCHASEDONEOFSKUS = 5;
	const DISCOUNTREQUIREMENTS_PURCHASEDONEOFSKUAFTERDATE = 6;
	const DISCOUNTREQUIREMENTS_PURCHASEDONEOFSKUWITHINNDAYS = 7;
	const DISCOUNTREQUIREMENTS_CUSTOMERASSIGNED = 8;
	const DISCOUNTREQUIREMENTS_CATEGORYASSIGNED = 9;

	const DISCOUNTTYPE_ALLSKUS = 1;
	const DISCOUNTTYPE_CATEGORYASSIGNED = 2;
	const DISCOUNTTYPE_FREESHIPPING = 3;
	const DISCOUNTTYPE_MOSTEXPENSIVESKU = 4;
	const DISCOUNTTYPE_WHOLEORDER = 5;
	const DISCOUNTTYPE_WHOLEORDEREXCEPTSKUS = 6;

	const DISCOUNTLIMIT_FIRSTNCUSTOMERS = 1;
	const DISCOUNTLIMIT_ONETIMEONLY = 2;
	const DISCOUNTLIMIT_ONETIMEPERCUSTOMER = 3;
	const DISCOUNTLIMIT_UNLIMITED = 4;

	const DISCOUNTSTATUS_PENDING = 1;
	const DISCOUNTSTATUS_ACTIVE = 2;
	const DISCOUNTSTATUS_EXPIRED = 3;
	const DISCOUNTSTATUS_DISABLED = 4;

	const ORDER_PAYMENT_STATUS_CANCELLED = 'c';
	const ORDER_PAYMENT_STATUS_UNPROCESSED = 'u';
	const ORDER_PAYMENT_STATUS_PENDING = 'n';
	const ORDER_PAYMENT_STATUS_PAID = 'p';

	const TAX_LEVEL_GRANULARITY_GLOBAL = 1; // Global
	const TAX_LEVEL_GRANULARITY_COUNTRY = 2; // Country
	const TAX_LEVEL_GRANULARITY_STATE = 3; // State
	const TAX_LEVEL_GRANULARITY_CITY = 4; // City/Zip - Not yet implemented
}