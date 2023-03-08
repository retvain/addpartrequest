<?php

declare(strict_types=1);

namespace App;

use DateTime;
use DOMDocument;
use Exception;
use Spatie\ArrayToXml\ArrayToXml;
use Webpatser\Uuid\Uuid;

class Generator
{
    /**
     * @var array
     */
    private $data;
    /**
     * @var XmlValidator
     */
    private $xmlValidator;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->xmlValidator = new XmlValidator;
    }

    /**
     * @throws \Exception
     */
    public function generateRequest(): string
    {
        $addRecordRequest = [];
        $resArr           = [];
        $date             = date(DateTime::ATOM);

        // формируем header
        $resArr['gar:header'] = [
            'gar:uid'     => Uuid::generate(4)->string,
            'gar:created' => $date,
        ];


            $addRecordRequest['gar:addRecordRequest'][1] = [
                '_attributes'              => [
                    'gar:requestId' => 1,
                ],
                'gar:orgRegNum'            => $this->data['orgRegNum'],
                'gar:orgAddress'           => $this->data['orgAddress'],
                'gar:communicationPartner' => [
                    'gar:title'        => $this->data['title'],
                    'gar:organization' => $this->data['organization'],
                    'gar:authority'    => $this->data['authority'],
                    'gar:phone'        => $this->data['phone'],
                    'gar:email'        => $this->data['email'],
                ],
                'gar:communicationService' => [
                    'gar:operatorUid' => $this->data['operatorUid'],
                    'gar:isActive'    => $this->data['isActive'],
                    'gar:isSecure'    => $this->data['isSecure'],
                ],
                'gar:justification'        => $this->data['justification'],

            ];

        $resArr['gar:docAddParticipantsRequest'] = [
            '_attributes'                => [
                'gar:docUid' => Uuid::generate(4)->string,
            ],
            'gar:docNumber'              => $this->data['docNumber'],
            'gar:docCreated'             => $date,
            'gar:operatorUid'            => $this->data['operatorUid'],
            'gar:addParticipantsRequest' => $addRecordRequest,
        ];

        $xml = $this->makeXml($resArr);
        if ($this->xmlValidator->validateXml($xml)) {
            return $xml;
        } else {
            throw new Exception("Ошибка валидации XML");
        }
    }

    private function makeXml(array $resArr): string
    {
        $root = [
            'rootElementName' => 'gar:container',
            '_attributes'     => [
                'xmlns'       => 'urn:IEDMS:ADDRESSEES',
                'xmlns:gar'   => 'urn:IEDMS:ADDRESSEES',
                'gar:version' => '2.7.1',
                'xmlns:xsi'   => 'http://www.w3.org/2001/XMLSchema-instance',
            ],
        ];

        $rawXml = ArrayToXml::convert(
            $resArr,
            $root,
            true,
            'UTF-8',
            '1.0'
        );

        $dom                     = new DOMDocument;
        $dom->preserveWhiteSpace = false;
        $dom->loadXML($rawXml);
        $dom->formatOutput = true;
        $xml               = $dom->saveXML();

        return $xml;
    }
}