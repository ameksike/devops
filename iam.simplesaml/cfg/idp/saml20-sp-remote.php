<?php
/**
 * SAML 2.0 remote SP metadata for SimpleSAMLphp.
 *
 * See: https://simplesamlphp.org/docs/stable/simplesamlphp-reference-sp-remote
 */

if (!getenv('SIMPLESAMLPHP_SP_ENTITY_ID')) {
    throw new UnexpectedValueException('SIMPLESAMLPHP_SP_ENTITY_ID is not defined as an environment variable.');
}
if (!getenv('SIMPLESAMLPHP_SP_ASSERTION_CONSUMER_SERVICE')) {
    throw new UnexpectedValueException('SIMPLESAMLPHP_SP_ASSERTION_CONSUMER_SERVICE is not defined as an environment variable.');
}

$metadata[getenv('SIMPLESAMLPHP_SP_ENTITY_ID')] = array(
    'AssertionConsumerService' => getenv('SIMPLESAMLPHP_SP_ASSERTION_CONSUMER_SERVICE'),
    'SingleLogoutService' => getenv('SIMPLESAMLPHP_SP_SINGLE_LOGOUT_SERVICE'),
);

$metadata['http://localhost:4000/v1/oauth/metadata/dev.thy.com'] = [
    'entityid' => 'http://localhost:4000/v1/oauth/metadata/dev.thy.com',
    'contacts' => [],
    'metadata-set' => 'saml20-sp-remote',
    'AssertionConsumerService' => [
        [
            'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
            'Location' => 'http://localhost:4000/v1/oauth/authorize',
            'index' => 1,
            'isDefault' => true,
        ],
    ],
    'SingleLogoutService' => [
        [
            'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
            'Location' => 'http://localhost:4000/v1/oauth/revoke?type=callback',
        ],
    ],
    'NameIDFormat' => 'urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified',
    'keys' => [
        [
            'encryption' => false,
            'signing' => true,
            'type' => 'X509Certificate',
            'X509Certificate' => 'MIIF0zCCA7ugAwIBAgIURrox/gEMteB5ADa2Wi/RSX50PYcwDQYJKoZIhvcNAQELBQAweTELMAkGA1UEBhMCVVMxFDASBgNVBAgMC0Nvbm5lY3RpY3V0MRAwDgYDVQQHDAdDZW50cmFsMRYwFAYDVQQKDA1BcnRPZlBvc3NpYmxlMRMwEQYDVQQLDApCbG9nV3JpdGVyMRUwEwYDVQQDDAxKZWZmcnlIb3VzZXIwHhcNMjMwMzEzMjMyNjQ5WhcNMjUwODI5MjMyNjQ5WjB5MQswCQYDVQQGEwJVUzEUMBIGA1UECAwLQ29ubmVjdGljdXQxEDAOBgNVBAcMB0NlbnRyYWwxFjAUBgNVBAoMDUFydE9mUG9zc2libGUxEzARBgNVBAsMCkJsb2dXcml0ZXIxFTATBgNVBAMMDEplZmZyeUhvdXNlcjCCAiIwDQYJKoZIhvcNAQEBBQADggIPADCCAgoCggIBANYaT2Uv4vGkk8jLcP/r0bQ7Ob1xfZ5m03pWj3yBs3hNWwNTqxITVwrW+N6/q3Uo4wAnvpENRucO/lN4Ua8PzMNUr2PxSBYJOFIPEbRVkA9RvuOZLAjeLQKCg0GvrXBr5dQT2TDGwgi/vq62DI5D9DKCrKWFpIeUm63Rp4i+Gah3ridRxi7Rbin0x6EssMANjSlUWseZ1Zxzu8PgIyBTFMJqlMfbBRrDfFm+ydmH0bpip61Q+vsnbzq2GW2z37zo5YKodmTODcp+aBTlnZdxfZCYSlZLFdB14MMDgy+sbUT7CKtn1Hfko/cX9bpMAa7d+J195DfXM6Pr+X/OLZThu0yWX59FU/oa6V8Yutnb6Zn45fcSytFXZxmVx0QNDjJEMkLSQiXzThq9BJ+T7+KjFKh3C9oMvuT7963i//vPFi4/ow8CqKdX9AropgpWo9fIMT/AjdzTRGjEaza0P/Yo0bJ3ugadxWngcRaISJdY1f3qacSJISiKIQFg44zAFnEucE5fkXnFFzQVU3CvH2WgjlIbEQ35ZBmLBj3RzsR9T9Z6oa2uqVoOn80aF4OtqxgSXhgfPuLYbLjemrhM3UktkuPPvu0YGr1MoVVTneZ5RutrUfmYQEErIgRT+CKBcEpLHuute48kHPpLD7KAGcwsCem8AkgxauxDF+z8VnM7cMznAgMBAAGjUzBRMB0GA1UdDgQWBBTy0Afk6rVv6mnH1LW4nLwio3FU5jAfBgNVHSMEGDAWgBTy0Afk6rVv6mnH1LW4nLwio3FU5jAPBgNVHRMBAf8EBTADAQH/MA0GCSqGSIb3DQEBCwUAA4ICAQCigTjYr8UxknAO7yh8SVP37DKoxxOGG2wiDUPsPOO80z77oymt4D69eOvNe7XMGenNhifiVz/LigJSjAfMWvCZf9S9nSac6xgUoGx1UCajO4AHUAjRPgqE2M54VLik2zKAfppEwv3YKD1luCqH57tTlj6hgIGJ/f60SFauSW0clJ+gs5wwi9b4hyS09n6vuqL9hvlTlAPZXEiycoI2OqWNrTl1piR8htHLLXg47SzlxR+0Bhxcl/jk4sZIZ0as8HJy4EsrS0O3XY9osVFkCuv0Sz9Xi3fX1Hp2i7iwnqjAMRTdKytvFvPFkiucrq4QGBk8vaMpFlGxCLReYFay3+SWFOxev2rsMZFoMknhfbQSNzJTFaS4u3sWMoJBDE4pfB8iz80AoFptnIKqthGyTfGeabhcAVNh6Hgih2bTBacBdYI3abHgDUdDw2Lj3kxH1m5lfuLk9zcqJKwfFP1fjySvfJINarWaYi2qHx05RWGa68stlGUMlhp6zp2onsXpQz3vqVwLIliQiOxOcctckXJTcsQ35WcR7mFIJr1pS1oSMv8vC7zLGVMT5RqB1j46viyndNdbh84+UCChC94bjerEZU1E/GZtEJaQUG+oiiXJlyXajrNvIaMPflO7BZtcdyHpqIEPR6DgDOxvkpJugfecfS0KWOH85dZK6eRgxmQ43Q==',
        ],
        [
            'encryption' => true,
            'signing' => false,
            'type' => 'X509Certificate',
            'X509Certificate' => 'MIIF0zCCA7ugAwIBAgIURrox/gEMteB5ADa2Wi/RSX50PYcwDQYJKoZIhvcNAQELBQAweTELMAkGA1UEBhMCVVMxFDASBgNVBAgMC0Nvbm5lY3RpY3V0MRAwDgYDVQQHDAdDZW50cmFsMRYwFAYDVQQKDA1BcnRPZlBvc3NpYmxlMRMwEQYDVQQLDApCbG9nV3JpdGVyMRUwEwYDVQQDDAxKZWZmcnlIb3VzZXIwHhcNMjMwMzEzMjMyNjQ5WhcNMjUwODI5MjMyNjQ5WjB5MQswCQYDVQQGEwJVUzEUMBIGA1UECAwLQ29ubmVjdGljdXQxEDAOBgNVBAcMB0NlbnRyYWwxFjAUBgNVBAoMDUFydE9mUG9zc2libGUxEzARBgNVBAsMCkJsb2dXcml0ZXIxFTATBgNVBAMMDEplZmZyeUhvdXNlcjCCAiIwDQYJKoZIhvcNAQEBBQADggIPADCCAgoCggIBANYaT2Uv4vGkk8jLcP/r0bQ7Ob1xfZ5m03pWj3yBs3hNWwNTqxITVwrW+N6/q3Uo4wAnvpENRucO/lN4Ua8PzMNUr2PxSBYJOFIPEbRVkA9RvuOZLAjeLQKCg0GvrXBr5dQT2TDGwgi/vq62DI5D9DKCrKWFpIeUm63Rp4i+Gah3ridRxi7Rbin0x6EssMANjSlUWseZ1Zxzu8PgIyBTFMJqlMfbBRrDfFm+ydmH0bpip61Q+vsnbzq2GW2z37zo5YKodmTODcp+aBTlnZdxfZCYSlZLFdB14MMDgy+sbUT7CKtn1Hfko/cX9bpMAa7d+J195DfXM6Pr+X/OLZThu0yWX59FU/oa6V8Yutnb6Zn45fcSytFXZxmVx0QNDjJEMkLSQiXzThq9BJ+T7+KjFKh3C9oMvuT7963i//vPFi4/ow8CqKdX9AropgpWo9fIMT/AjdzTRGjEaza0P/Yo0bJ3ugadxWngcRaISJdY1f3qacSJISiKIQFg44zAFnEucE5fkXnFFzQVU3CvH2WgjlIbEQ35ZBmLBj3RzsR9T9Z6oa2uqVoOn80aF4OtqxgSXhgfPuLYbLjemrhM3UktkuPPvu0YGr1MoVVTneZ5RutrUfmYQEErIgRT+CKBcEpLHuute48kHPpLD7KAGcwsCem8AkgxauxDF+z8VnM7cMznAgMBAAGjUzBRMB0GA1UdDgQWBBTy0Afk6rVv6mnH1LW4nLwio3FU5jAfBgNVHSMEGDAWgBTy0Afk6rVv6mnH1LW4nLwio3FU5jAPBgNVHRMBAf8EBTADAQH/MA0GCSqGSIb3DQEBCwUAA4ICAQCigTjYr8UxknAO7yh8SVP37DKoxxOGG2wiDUPsPOO80z77oymt4D69eOvNe7XMGenNhifiVz/LigJSjAfMWvCZf9S9nSac6xgUoGx1UCajO4AHUAjRPgqE2M54VLik2zKAfppEwv3YKD1luCqH57tTlj6hgIGJ/f60SFauSW0clJ+gs5wwi9b4hyS09n6vuqL9hvlTlAPZXEiycoI2OqWNrTl1piR8htHLLXg47SzlxR+0Bhxcl/jk4sZIZ0as8HJy4EsrS0O3XY9osVFkCuv0Sz9Xi3fX1Hp2i7iwnqjAMRTdKytvFvPFkiucrq4QGBk8vaMpFlGxCLReYFay3+SWFOxev2rsMZFoMknhfbQSNzJTFaS4u3sWMoJBDE4pfB8iz80AoFptnIKqthGyTfGeabhcAVNh6Hgih2bTBacBdYI3abHgDUdDw2Lj3kxH1m5lfuLk9zcqJKwfFP1fjySvfJINarWaYi2qHx05RWGa68stlGUMlhp6zp2onsXpQz3vqVwLIliQiOxOcctckXJTcsQ35WcR7mFIJr1pS1oSMv8vC7zLGVMT5RqB1j46viyndNdbh84+UCChC94bjerEZU1E/GZtEJaQUG+oiiXJlyXajrNvIaMPflO7BZtcdyHpqIEPR6DgDOxvkpJugfecfS0KWOH85dZK6eRgxmQ43Q==',
        ],
    ],
    'validate.authnrequest' => true,
];

