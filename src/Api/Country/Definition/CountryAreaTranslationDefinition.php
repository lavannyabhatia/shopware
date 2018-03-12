<?php declare(strict_types=1);

namespace Shopware\Api\Country\Definition;

use Shopware\Api\Country\Collection\CountryAreaTranslationBasicCollection;
use Shopware\Api\Country\Collection\CountryAreaTranslationDetailCollection;
use Shopware\Api\Country\Event\CountryAreaTranslation\CountryAreaTranslationDeletedEvent;
use Shopware\Api\Country\Event\CountryAreaTranslation\CountryAreaTranslationWrittenEvent;
use Shopware\Api\Country\Repository\CountryAreaTranslationRepository;
use Shopware\Api\Country\Struct\CountryAreaTranslationBasicStruct;
use Shopware\Api\Country\Struct\CountryAreaTranslationDetailStruct;
use Shopware\Api\Entity\EntityDefinition;
use Shopware\Api\Entity\EntityExtensionInterface;
use Shopware\Api\Entity\Field\FkField;
use Shopware\Api\Entity\Field\ManyToOneAssociationField;
use Shopware\Api\Entity\Field\StringField;
use Shopware\Api\Entity\Field\VersionField;
use Shopware\Api\Entity\FieldCollection;
use Shopware\Api\Entity\Write\Flag\PrimaryKey;
use Shopware\Api\Entity\Write\Flag\Required;
use Shopware\Api\Language\Definition\LanguageDefinition;

class CountryAreaTranslationDefinition extends EntityDefinition
{
    /**
     * @var FieldCollection
     */
    protected static $primaryKeys;

    /**
     * @var FieldCollection
     */
    protected static $fields;

    /**
     * @var EntityExtensionInterface[]
     */
    protected static $extensions = [];

    public static function getEntityName(): string
    {
        return 'country_area_translation';
    }

    public static function getFields(): FieldCollection
    {
        if (self::$fields) {
            return self::$fields;
        }

        self::$fields = new FieldCollection([
            (new FkField('country_area_id', 'countryAreaId', CountryAreaDefinition::class))->setFlags(new PrimaryKey(), new Required()),
            new VersionField(),

            (new FkField('language_id', 'languageId', LanguageDefinition::class))->setFlags(new PrimaryKey(), new Required()),

            (new StringField('name', 'name'))->setFlags(new Required()),
            new ManyToOneAssociationField('countryArea', 'country_area_id', CountryAreaDefinition::class, false),
            new ManyToOneAssociationField('language', 'language_id', LanguageDefinition::class, false),
        ]);

        foreach (self::$extensions as $extension) {
            $extension->extendFields(self::$fields);
        }

        return self::$fields;
    }

    public static function getRepositoryClass(): string
    {
        return CountryAreaTranslationRepository::class;
    }

    public static function getBasicCollectionClass(): string
    {
        return CountryAreaTranslationBasicCollection::class;
    }

    public static function getDeletedEventClass(): string
    {
        return CountryAreaTranslationDeletedEvent::class;
    }

    public static function getWrittenEventClass(): string
    {
        return CountryAreaTranslationWrittenEvent::class;
    }

    public static function getBasicStructClass(): string
    {
        return CountryAreaTranslationBasicStruct::class;
    }

    public static function getTranslationDefinitionClass(): ?string
    {
        return null;
    }

    public static function getDetailStructClass(): string
    {
        return CountryAreaTranslationDetailStruct::class;
    }

    public static function getDetailCollectionClass(): string
    {
        return CountryAreaTranslationDetailCollection::class;
    }
}
