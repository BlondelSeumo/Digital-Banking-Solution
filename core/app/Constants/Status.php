<?php

namespace App\Constants;

class Status{

    const ENABLE = 1;
    const DISABLE = 0;

    const YES = 1;
    const NO = 0;

    const VERIFIED = 1;
    const UNVERIFIED = 0;

    const PAYMENT_INITIATE = 0;
    const PAYMENT_SUCCESS = 1;
    const PAYMENT_PENDING = 2;
    const PAYMENT_REJECT = 3;

    CONST TICKET_OPEN = 0;
    CONST TICKET_ANSWER = 1;
    CONST TICKET_REPLY = 2;
    CONST TICKET_CLOSE = 3;

    CONST PRIORITY_LOW = 1;
    CONST PRIORITY_MEDIUM = 2;
    CONST PRIORITY_HIGH = 3;

    const USER_ACTIVE = 1;
    const USER_BAN = 0;

    const STAFF_ACTIVE = 1;
    const STAFF_BAN = 0;

    const KYC_UNVERIFIED = 0;
    const KYC_PENDING = 2;
    const KYC_VERIFIED = 1;

    const GOOGLE_PAY = 5001;

    const CUR_BOTH = 1;
    const CUR_TEXT = 2;
    const CUR_SYM = 3;

    const FDR_RUNNING = 1;
    const FDR_CLOSED = 2;

    const DPS_RUNNING = 1;
    const DPS_MATURED = 2;
    const DPS_CLOSED = 0;

    const LOAN_PENDING = 0;
    const LOAN_RUNNING = 1;
    const LOAN_PAID = 2;
    const LOAN_REJECTED = 3;

    const TRANSFER_PENDING = 0;
    const TRANSFER_COMPLETED = 1;
    const TRANSFER_REJECTED = 2;

    const ROLE_ACCOUNT_OFFICER = 0;
    const ROLE_MANAGER = 1;

}
