<?php declare(strict_types=1);

namespace Shopware\Framework\Writer\Resource;

use Shopware\Context\Struct\TranslationContext;
use Shopware\Framework\Event\FilterOptionTranslationWrittenEvent;
use Shopware\Framework\Write\Field\FkField;
use Shopware\Framework\Write\Field\ReferenceField;
use Shopware\Framework\Write\Field\StringField;
use Shopware\Framework\Write\Flag\Required;
use Shopware\Framework\Write\WriteResource;
use Shopware\Shop\Writer\Resource\ShopWriteResource;

class FilterOptionTranslationWriteResource extends WriteResource
{
    protected const NAME_FIELD = 'name';

    public function __construct()
    {
        parent::__construct('filter_option_translation');

        $this->fields[self::NAME_FIELD] = (new StringField('name'))->setFlags(new Required());
        $this->fields['filterOption'] = new ReferenceField('filterOptionUuid', 'uuid', FilterOptionWriteResource::class);
        $this->primaryKeyFields['filterOptionUuid'] = (new FkField('filter_option_uuid', FilterOptionWriteResource::class, 'uuid'))->setFlags(new Required());
        $this->fields['language'] = new ReferenceField('languageUuid', 'uuid', ShopWriteResource::class);
        $this->primaryKeyFields['languageUuid'] = (new FkField('language_uuid', ShopWriteResource::class, 'uuid'))->setFlags(new Required());
    }

    public function getWriteOrder(): array
    {
        return [
            FilterOptionWriteResource::class,
            ShopWriteResource::class,
            self::class,
        ];
    }

    public static function createWrittenEvent(array $updates, TranslationContext $context, array $rawData = [], array $errors = []): FilterOptionTranslationWrittenEvent
    {
        $event = new FilterOptionTranslationWrittenEvent($updates[self::class] ?? [], $context, $rawData, $errors);

        unset($updates[self::class]);

        /**
         * @var WriteResource
         * @var string[]      $identifiers
         */
        foreach ($updates as $class => $identifiers) {
            $event->addEvent($class::createWrittenEvent($updates, $context));
        }

        return $event;
    }
}
