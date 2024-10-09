<?php
// These attributes mimic those of Azure AD.
$tplUser = array(
    'http://schemas.microsoft.com/identity/claims/tenantid' => 'ab4f07dc-b661-48a3-a173-d0103d6981b2',
    'http://schemas.microsoft.com/identity/claims/objectidentifier' => '',
    'http://schemas.microsoft.com/identity/claims/displayname' => '',
    'http://schemas.microsoft.com/ws/2008/06/identity/claims/groups' => array(),
    'http://schemas.microsoft.com/identity/claims/identityprovider' => 'https://sts.windows.net/da2a1472-abd3-47c9-95a4-4a0068312122/',
    'http://schemas.microsoft.com/claims/authnmethodsreferences' => array('http://schemas.microsoft.com/ws/2008/06/identity/authenticationmethod/password', 'http://schemas.microsoft.com/claims/multipleauthn'),
    'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress' => '',
    'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/givenname' => '',
    'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/surname' => '',
    'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/name' => ''
);

$config = array(
    'admin' => array(
        'core:AdminPassword',
    ),
    'example-userpass' => array(
        'exampleauth:UserPass',
        'user0:user0' => array_merge($tplUser, array(
            'uid' => 'f2d75402-e1ae-40fe-8cc9-98ca1ab9cd2e',
            'cn' => 'User0 Tester',
            'email' => 'user0@example.com',
            'givenName' => 'Tester',
            'surname' => 'User0',
            'sn' => 'user0@example.com'
        )),
        'user1:user1' => array_merge($tplUser, array(
            'uid' => 'f2d75402-e1ae-40fe-8cc9-98ca1ab9cd7e',
            'cn' => 'User1 Oroo',
            'email' => 'user1@example.com',
            'givenName' => 'Oroo',
            'surname' => 'User1',
            'sn' => 'user1@example.com',
            // 'rol' => 'rem2',
            'expiration' => '1m',
            'refresh' => '3m',
            'secret' => '8976978976698',
            // 'company' => 'CCM',
            'company' => 'AFR'
        )),
        'user2:user2' => array_merge($tplUser, array(
            'uid' => 'f2d75402-e1ae-40fe-8cc9-98ca1ab9cd6e',
            'cn' => 'User2 Mito',
            'email' => 'user2@example.com',
            'givenName' => 'User2',
            'surname' => 'Mito',
            'sn' => 'user2@example.com',
            'rol' => 'rem2',
            'company' => 'AFR'
        )),
        'user3:user3' => array_merge($tplUser, array(
            'uid' => 'f2d75402-e1ae-40fe-8cc9-98ca1ab9cd6e',
            'cn' => 'User3 Tieso',
            'email' => 'user3@example.com',
            'givenName' => 'Tonn',
            'surname' => 'Tieso',
            'sn' => 'user3@example.com',
            'rol' => 'rem3'
        )),
    ),
);
