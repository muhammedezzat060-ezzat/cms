<?php
/**
 * AUSR CMS - Content Mapping Registry
 * This file acts as the bridge between JSON keys and the UI labels.
 * It ensures Data Keys match the API expectations.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class AUSR_Content_Map {

    private static $map_file = AUSR_CMS_DIR . 'content.json';

    /**
     * Get the full structure for UI building
     */
    public static function get_registry() {
        return [
            'home' => [
                'label' => 'الصفحة الرئيسية',
                'sections' => [
                    'hero' => [
                        'label' => 'قسم البطل (Hero)',
                        'fields' => [
                            'home__hero__badge'    => ['label' => 'شارة الترحيب', 'type' => 'text', 'group' => 'الصفحة الرئيسية - قسم البطل', 'description' => 'شارة صغيرة تظهر فوق العنوان الرئيسي'],
                            'home__hero__title_1'  => ['label' => 'العنوان (سطر 1)', 'type' => 'text', 'group' => 'الصفحة الرئيسية - قسم البطل', 'description' => 'السطر الأول من العنوان الرئيسي'],
                            'home__hero__title_2'  => ['label' => 'العنوان (سطر 2)', 'type' => 'text', 'group' => 'الصفحة الرئيسية - قسم البطل', 'description' => 'السطر الثاني من العنوان الرئيسي'],
                            'home__hero__title_3'  => ['label' => 'العنوان (سطر 3)', 'type' => 'text', 'group' => 'الصفحة الرئيسية - قسم البطل', 'description' => 'السطر الثالث من العنوان الرئيسي'],
                            'home__hero__subtitle' => ['label' => 'الوصف الفرعي', 'type' => 'textarea', 'group' => 'الصفحة الرئيسية - قسم البطل', 'description' => 'الوصف الفرعي أسفل العنوان الرئيسي'],
                        ]
                    ],
                    'stats_card' => [
                        'label' => 'بطاقات الإحصائيات',
                        'fields' => [
                            'home__stats__card__label'             => ['label' => 'عنوان القسم', 'type' => 'text', 'group' => 'الصفحة الرئيسية - بطاقات الإحصائيات', 'description' => 'عنوان قسم بطاقات الإحصائيات'],
                            'home__stats__card__programs'          => ['label' => 'عدد البرامج', 'type' => 'text', 'group' => 'الصفحة الرئيسية - بطاقات الإحصائيات', 'description' => 'رقم عدد البرامج الأكاديمية'],
                            'home__stats__card__programs_lbl'      => ['label' => 'تسمية البرامج', 'type' => 'text', 'group' => 'الصفحة الرئيسية - بطاقات الإحصائيات', 'description' => 'تسمية بطاقة البرامج'],
                            'home__stats__card__partnerships'      => ['label' => 'عدد الشراكات', 'type' => 'text', 'group' => 'الصفحة الرئيسية - بطاقات الإحصائيات', 'description' => 'رقم عدد الشراكات الدولية'],
                            'home__stats__card__partnerships_lbl'  => ['label' => 'تسمية الشراكات', 'type' => 'text', 'group' => 'الصفحة الرئيسية - بطاقات الإحصائيات', 'description' => 'تسمية بطاقة الشراكات'],
                            'home__stats__card__graduates'         => ['label' => 'عدد الخريجين', 'type' => 'text', 'group' => 'الصفحة الرئيسية - بطاقات الإحصائيات', 'description' => 'رقم عدد الخريجين'],
                            'home__stats__card__graduates_lbl'     => ['label' => 'تسمية الخريجين', 'type' => 'text', 'group' => 'الصفحة الرئيسية - بطاقات الإحصائيات', 'description' => 'تسمية بطاقة الخريجين'],
                            'home__stats__card__accreditation'     => ['label' => 'نسبة الاعتماد', 'type' => 'text', 'group' => 'الصفحة الرئيسية - بطاقات الإحصائيات', 'description' => 'نسبة الاعتماد الأكاديمي'],
                            'home__stats__card__accreditation_lbl' => ['label' => 'تسمية الاعتماد', 'type' => 'text', 'group' => 'الصفحة الرئيسية - بطاقات الإحصائيات', 'description' => 'تسمية بطاقة الاعتماد'],
                        ]
                    ],
                    'stats_bar' => [
                        'label' => 'شريط الإحصائيات',
                        'fields' => [
                            'home__stats__bar__programs'          => ['label' => 'عدد البرامج', 'type' => 'text', 'group' => 'الصفحة الرئيسية - شريط الإحصائيات', 'description' => 'رقم عدد البرامج في الشريط'],
                            'home__stats__bar__programs_lbl'      => ['label' => 'تسمية البرامج', 'type' => 'text', 'group' => 'الصفحة الرئيسية - شريط الإحصائيات', 'description' => 'تسمية البرامج في الشريط'],
                            'home__stats__bar__partnerships'      => ['label' => 'عدد الشراكات', 'type' => 'text', 'group' => 'الصفحة الرئيسية - شريط الإحصائيات', 'description' => 'رقم عدد الشراكات في الشريط'],
                            'home__stats__bar__partnerships_lbl'  => ['label' => 'تسمية الشراكات', 'type' => 'text', 'group' => 'الصفحة الرئيسية - شريط الإحصائيات', 'description' => 'تسمية الشراكات في الشريط'],
                            'home__stats__bar__graduates'         => ['label' => 'عدد الخريجين', 'type' => 'text', 'group' => 'الصفحة الرئيسية - شريط الإحصائيات', 'description' => 'رقم عدد الخريجين في الشريط'],
                            'home__stats__bar__graduates_lbl'     => ['label' => 'تسمية الخريجين', 'type' => 'text', 'group' => 'الصفحة الرئيسية - شريط الإحصائيات', 'description' => 'تسمية الخريجين في الشريط'],
                            'home__stats__bar__accreditation'     => ['label' => 'نسبة الاعتماد', 'type' => 'text', 'group' => 'الصفحة الرئيسية - شريط الإحصائيات', 'description' => 'نسبة الاعتماد في الشريط'],
                            'home__stats__bar__accreditation_lbl' => ['label' => 'تسمية الاعتماد', 'type' => 'text', 'group' => 'الصفحة الرئيسية - شريط الإحصائيات', 'description' => 'تسمية الاعتماد في الشريط'],
                        ]
                    ],
                    'about' => [
                        'label' => 'قسم من نحن',
                        'fields' => [
                            'home__about__tag'     => ['label' => 'الوسم', 'type' => 'text', 'group' => 'الصفحة الرئيسية - قسم من نحن', 'description' => 'وسم صغير يظهر فوق العنوان'],
                            'home__about__heading' => ['label' => 'العنوان', 'type' => 'text', 'group' => 'الصفحة الرئيسية - قسم من نحن', 'description' => 'عنوان قسم من نحن'],
                            'home__about__body'    => ['label' => 'النص', 'type' => 'textarea', 'group' => 'الصفحة الرئيسية - قسم من نحن', 'description' => 'نص وصف قسم من نحن'],
                        ]
                    ],
                    'programs' => [
                        'label' => 'قسم البرامج',
                        'fields' => [
                            'home__programs__heading' => ['label' => 'العنوان', 'type' => 'text', 'group' => 'الصفحة الرئيسية - قسم البرامج', 'description' => 'عنوان قسم البرامج الأكاديمية'],
                        ]
                    ],
                    'vision' => [
                        'label' => 'قسم الرؤية',
                        'fields' => [
                            'home__vision__quote' => ['label' => 'اقتباس الرؤية', 'type' => 'textarea', 'group' => 'الصفحة الرئيسية - قسم الرؤية', 'description' => 'اقتباس رؤية الجامعة'],
                        ]
                    ]
                ]
            ],
            'programs' => [
                'label' => 'البرامج الأكاديمية',
                'sections' => [
                    'hero' => [
                        'label' => 'قسم البطل',
                        'fields' => [
                            'programs__hero__eyebrow' => ['label' => 'الوسم', 'type' => 'text', 'group' => 'البرامج الأكاديمية - قسم البطل', 'description' => 'وسم صغير يظهر فوق العنوان الرئيسي'],
                            'programs__hero__title'   => ['label' => 'عنوان الصفحة', 'type' => 'text', 'group' => 'البرامج الأكاديمية - قسم البطل', 'description' => 'العنوان الرئيسي لصفحة البرامج'],
                            'programs__hero__desc'    => ['label' => 'وصف الصفحة', 'type' => 'textarea', 'group' => 'البرامج الأكاديمية - قسم البطل', 'description' => 'الوصف الفرعي لصفحة البرامج'],
                        ]
                    ],
                    'filters' => [
                        'label' => 'أزرار الفلتر',
                        'fields' => [
                            'programs__filter__all'         => ['label' => 'الكل', 'type' => 'text', 'group' => 'البرامج الأكاديمية - أزرار الفلتر', 'description' => 'زر فلتر عرض جميع البرامج'],
                            'programs__filter__masters'     => ['label' => 'ماجستير', 'type' => 'text', 'group' => 'البرامج الأكاديمية - أزرار الفلتر', 'description' => 'زر فلتر برامج الماجستير'],
                            'programs__filter__phd'         => ['label' => 'دكتوراه', 'type' => 'text', 'group' => 'البرامج الأكاديمية - أزرار الفلتر', 'description' => 'زر فلتر برامج الدكتوراه'],
                            'programs__filter__research'    => ['label' => 'بحث علمي', 'type' => 'text', 'group' => 'البرامج الأكاديمية - أزرار الفلتر', 'description' => 'زر فلتر برامج البحث العلمي'],
                            'programs__filter__partnership' => ['label' => 'شراكة دولية', 'type' => 'text', 'group' => 'البرامج الأكاديمية - أزرار الفلتر', 'description' => 'زر فلتر الشراكات الدولية'],
                        ]
                    ],
                    'list' => [
                        'label' => 'قائمة البرامج',
                        'fields' => [
                            'programs__list__item_01__cat'       => ['label' => 'البرنامج 1: الفئة', 'type' => 'text', 'group' => 'البرامج الأكاديمية - قائمة البرامج', 'description' => 'فئة البرنامج الأول (ماجستير/دكتوراه/إلخ)'],
                            'programs__list__item_01__icon'      => ['label' => 'البرنامج 1: الأيقونة', 'type' => 'text', 'group' => 'البرامج الأكاديمية - قائمة البرامج', 'description' => 'أيقونة البرنامج الأول (emoji)'],
                            'programs__list__item_01__title'     => ['label' => 'البرنامج 1: العنوان', 'type' => 'text', 'group' => 'البرامج الأكاديمية - قائمة البرامج', 'description' => 'عنوان البرنامج الأول'],
                            'programs__list__item_01__desc'      => ['label' => 'البرنامج 1: الوصف', 'type' => 'textarea', 'group' => 'البرامج الأكاديمية - قائمة البرامج', 'description' => 'وصف البرنامج الأول'],
                            'programs__list__item_01__tag_1'     => ['label' => 'البرنامج 1: الشارة 1', 'type' => 'text', 'group' => 'البرامج الأكاديمية - قائمة البرامج', 'description' => 'الشارة الأولى للبرنامج الأول'],
                            'programs__list__item_01__tag_2'     => ['label' => 'البرنامج 1: الشارة 2', 'type' => 'text', 'group' => 'البرامج الأكاديمية - قائمة البرامج', 'description' => 'الشارة الثانية للبرنامج الأول'],
                            'programs__list__item_01__duration'  => ['label' => 'البرنامج 1: المدة', 'type' => 'text', 'group' => 'البرامج الأكاديمية - قائمة البرامج', 'description' => 'مدة البرنامج الأول'],
                            'programs__list__item_02__cat'       => ['label' => 'البرنامج 2: الفئة', 'type' => 'text', 'group' => 'البرامج الأكاديمية - قائمة البرامج', 'description' => 'فئة البرنامج الثاني'],
                            'programs__list__item_02__icon'      => ['label' => 'البرنامج 2: الأيقونة', 'type' => 'text', 'group' => 'البرامج الأكاديمية - قائمة البرامج', 'description' => 'أيقونة البرنامج الثاني'],
                            'programs__list__item_02__title'     => ['label' => 'البرنامج 2: العنوان', 'type' => 'text', 'group' => 'البرامج الأكاديمية - قائمة البرامج', 'description' => 'عنوان البرنامج الثاني'],
                            'programs__list__item_02__desc'      => ['label' => 'البرنامج 2: الوصف', 'type' => 'textarea', 'group' => 'البرامج الأكاديمية - قائمة البرامج', 'description' => 'وصف البرنامج الثاني'],
                            'programs__list__item_02__tag_1'     => ['label' => 'البرنامج 2: الشارة 1', 'type' => 'text', 'group' => 'البرامج الأكاديمية - قائمة البرامج', 'description' => 'الشارة الأولى للبرنامج الثاني'],
                            'programs__list__item_02__tag_2'     => ['label' => 'البرنامج 2: الشارة 2', 'type' => 'text', 'group' => 'البرامج الأكاديمية - قائمة البرامج', 'description' => 'الشارة الثانية للبرنامج الثاني'],
                            'programs__list__item_02__duration'  => ['label' => 'البرنامج 2: المدة', 'type' => 'text', 'group' => 'البرامج الأكاديمية - قائمة البرامج', 'description' => 'مدة البرنامج الثاني'],
                            'programs__list__item_03__cat'       => ['label' => 'البرنامج 3: الفئة', 'type' => 'text', 'group' => 'البرامج الأكاديمية - قائمة البرامج', 'description' => 'فئة البرنامج الثالث'],
                            'programs__list__item_03__icon'      => ['label' => 'البرنامج 3: الأيقونة', 'type' => 'text', 'group' => 'البرامج الأكاديمية - قائمة البرامج', 'description' => 'أيقونة البرنامج الثالث'],
                            'programs__list__item_03__title'     => ['label' => 'البرنامج 3: العنوان', 'type' => 'text', 'group' => 'البرامج الأكاديمية - قائمة البرامج', 'description' => 'عنوان البرنامج الثالث'],
                            'programs__list__item_03__desc'      => ['label' => 'البرنامج 3: الوصف', 'type' => 'textarea', 'group' => 'البرامج الأكاديمية - قائمة البرامج', 'description' => 'وصف البرنامج الثالث'],
                            'programs__list__item_03__tag_1'     => ['label' => 'البرنامج 3: الشارة 1', 'type' => 'text', 'group' => 'البرامج الأكاديمية - قائمة البرامج', 'description' => 'الشارة الأولى للبرنامج الثالث'],
                            'programs__list__item_03__tag_2'     => ['label' => 'البرنامج 3: الشارة 2', 'type' => 'text', 'group' => 'البرامج الأكاديمية - قائمة البرامج', 'description' => 'الشارة الثانية للبرنامج الثالث'],
                            'programs__list__item_03__duration'  => ['label' => 'البرنامج 3: المدة', 'type' => 'text', 'group' => 'البرامج الأكاديمية - قائمة البرامج', 'description' => 'مدة البرنامج الثالث'],
                            'programs__list__item_04__cat'       => ['label' => 'البرنامج 4: الفئة', 'type' => 'text', 'group' => 'البرامج الأكاديمية - قائمة البرامج', 'description' => 'فئة البرنامج الرابع'],
                            'programs__list__item_04__icon'      => ['label' => 'البرنامج 4: الأيقونة', 'type' => 'text', 'group' => 'البرامج الأكاديمية - قائمة البرامج', 'description' => 'أيقونة البرنامج الرابع'],
                            'programs__list__item_04__title'     => ['label' => 'البرنامج 4: العنوان', 'type' => 'text', 'group' => 'البرامج الأكاديمية - قائمة البرامج', 'description' => 'عنوان البرنامج الرابع'],
                            'programs__list__item_04__desc'      => ['label' => 'البرنامج 4: الوصف', 'type' => 'textarea', 'group' => 'البرامج الأكاديمية - قائمة البرامج', 'description' => 'وصف البرنامج الرابع'],
                            'programs__list__item_04__tag_1'     => ['label' => 'البرنامج 4: الشارة 1', 'type' => 'text', 'group' => 'البرامج الأكاديمية - قائمة البرامج', 'description' => 'الشارة الأولى للبرنامج الرابع'],
                            'programs__list__item_04__tag_2'     => ['label' => 'البرنامج 4: الشارة 2', 'type' => 'text', 'group' => 'البرامج الأكاديمية - قائمة البرامج', 'description' => 'الشارة الثانية للبرنامج الرابع'],
                            'programs__list__item_04__duration'  => ['label' => 'البرنامج 4: المدة', 'type' => 'text', 'group' => 'البرامج الأكاديمية - قائمة البرامج', 'description' => 'مدة البرنامج الرابع'],
                            'programs__list__item_05__cat'       => ['label' => 'البرنامج 5: الفئة', 'type' => 'text', 'group' => 'البرامج الأكاديمية - قائمة البرامج', 'description' => 'فئة البرنامج الخامس'],
                            'programs__list__item_05__icon'      => ['label' => 'البرنامج 5: الأيقونة', 'type' => 'text', 'group' => 'البرامج الأكاديمية - قائمة البرامج', 'description' => 'أيقونة البرنامج الخامس'],
                            'programs__list__item_05__title'     => ['label' => 'البرنامج 5: العنوان', 'type' => 'text', 'group' => 'البرامج الأكاديمية - قائمة البرامج', 'description' => 'عنوان البرنامج الخامس'],
                            'programs__list__item_05__desc'      => ['label' => 'البرنامج 5: الوصف', 'type' => 'textarea', 'group' => 'البرامج الأكاديمية - قائمة البرامج', 'description' => 'وصف البرنامج الخامس'],
                            'programs__list__item_05__tag_1'     => ['label' => 'البرنامج 5: الشارة 1', 'type' => 'text', 'group' => 'البرامج الأكاديمية - قائمة البرامج', 'description' => 'الشارة الأولى للبرنامج الخامس'],
                            'programs__list__item_05__tag_2'     => ['label' => 'البرنامج 5: الشارة 2', 'type' => 'text', 'group' => 'البرامج الأكاديمية - قائمة البرامج', 'description' => 'الشارة الثانية للبرنامج الخامس'],
                            'programs__list__item_05__duration'  => ['label' => 'البرنامج 5: المدة', 'type' => 'text', 'group' => 'البرامج الأكاديمية - قائمة البرامج', 'description' => 'مدة البرنامج الخامس'],
                            'programs__list__item_06__cat'       => ['label' => 'البرنامج 6: الفئة', 'type' => 'text', 'group' => 'البرامج الأكاديمية - قائمة البرامج', 'description' => 'فئة البرنامج السادس'],
                            'programs__list__item_06__icon'      => ['label' => 'البرنامج 6: الأيقونة', 'type' => 'text', 'group' => 'البرامج الأكاديمية - قائمة البرامج', 'description' => 'أيقونة البرنامج السادس'],
                            'programs__list__item_06__title'     => ['label' => 'البرنامج 6: العنوان', 'type' => 'text', 'group' => 'البرامج الأكاديمية - قائمة البرامج', 'description' => 'عنوان البرنامج السادس'],
                            'programs__list__item_06__desc'      => ['label' => 'البرنامج 6: الوصف', 'type' => 'textarea', 'group' => 'البرامج الأكاديمية - قائمة البرامج', 'description' => 'وصف البرنامج السادس'],
                            'programs__list__item_06__tag_1'     => ['label' => 'البرنامج 6: الشارة 1', 'type' => 'text', 'group' => 'البرامج الأكاديمية - قائمة البرامج', 'description' => 'الشارة الأولى للبرنامج السادس'],
                            'programs__list__item_06__tag_2'     => ['label' => 'البرنامج 6: الشارة 2', 'type' => 'text', 'group' => 'البرامج الأكاديمية - قائمة البرامج', 'description' => 'الشارة الثانية للبرنامج السادس'],
                            'programs__list__item_06__duration'  => ['label' => 'البرنامج 6: المدة', 'type' => 'text', 'group' => 'البرامج الأكاديمية - قائمة البرامج', 'description' => 'مدة البرنامج السادس'],
                        ]
                    ]
                ]
            ],
            'events' => [
                'label' => 'الفعاليات',
                'sections' => [
                    'hero' => [
                        'label' => 'قسم البطل',
                        'fields' => [
                            'events__hero__eyebrow' => ['label' => 'الوسم', 'type' => 'text', 'group' => 'الفعاليات - قسم البطل', 'description' => 'وسم صغير يظهر فوق العنوان الرئيسي'],
                            'events__hero__title'   => ['label' => 'عنوان الصفحة', 'type' => 'text', 'group' => 'الفعاليات - قسم البطل', 'description' => 'العنوان الرئيسي لصفحة الفعاليات'],
                            'events__hero__desc'    => ['label' => 'وصف الصفحة', 'type' => 'textarea', 'group' => 'الفعاليات - قسم البطل', 'description' => 'الوصف الفرعي لصفحة الفعاليات'],
                        ]
                    ],
                    'badges' => [
                        'label' => 'شارات الفعاليات',
                        'fields' => [
                            'events__badge__conf'    => ['label' => 'شارة مؤتمر', 'type' => 'text', 'group' => 'الفعاليات - شارات الفعاليات', 'description' => 'شارة تظهر على بطاقات المؤتمرات'],
                            'events__badge__workshop' => ['label' => 'شارة ورشة عمل', 'type' => 'text', 'group' => 'الفعاليات - شارات الفعاليات', 'description' => 'شارة تظهر على بطاقات ورش العمل'],
                            'events__badge__seminar' => ['label' => 'شارة ندوة', 'type' => 'text', 'group' => 'الفعاليات - شارات الفعاليات', 'description' => 'شارة تظهر على بطاقات الندوات'],
                        ]
                    ],
                    'featured' => [
                        'label' => 'الفعالية المميزة',
                        'fields' => [
                            'events__featured__tag'        => ['label' => 'الوسم', 'type' => 'text', 'group' => 'الفعاليات - الفعالية المميزة', 'description' => 'وسم يظهر فوق الفعالية المميزة'],
                            'events__featured__title'      => ['label' => 'عنوان الفعالية', 'type' => 'text', 'group' => 'الفعاليات - الفعالية المميزة', 'description' => 'عنوان الفعالية المميزة'],
                            'events__featured__desc'       => ['label' => 'وصف الفعالية', 'type' => 'textarea', 'group' => 'الفعاليات - الفعالية المميزة', 'description' => 'وصف الفعالية المميزة'],
                            'events__featured__dates'      => ['label' => 'التواريخ', 'type' => 'text', 'group' => 'الفعاليات - الفعالية المميزة', 'description' => 'تواريخ الفعالية المميزة'],
                            'events__featured__location'   => ['label' => 'الموقع', 'type' => 'text', 'group' => 'الفعاليات - الفعالية المميزة', 'description' => 'موقع الفعالية المميزة'],
                            'events__featured__attendees'  => ['label' => 'عدد المشاركين', 'type' => 'text', 'group' => 'الفعاليات - الفعالية المميزة', 'description' => 'عدد المشاركين المتوقع'],
                            'events__featured__day'        => ['label' => 'اليوم', 'type' => 'text', 'group' => 'الفعاليات - الفعالية المميزة', 'description' => 'يوم الفعالية المميزة'],
                            'events__featured__month'      => ['label' => 'الشهر', 'type' => 'text', 'group' => 'الفعاليات - الفعالية المميزة', 'description' => 'شهر الفعالية المميزة'],
                            'events__featured__year'       => ['label' => 'السنة', 'type' => 'text', 'group' => 'الفعاليات - الفعالية المميزة', 'description' => 'سنة الفعالية المميزة'],
                        ]
                    ],
                    'list' => [
                        'label' => 'قائمة الفعاليات',
                        'fields' => [
                            'events__list__item_01__date'  => ['label' => 'الفعالية 1: التاريخ', 'type' => 'text', 'group' => 'الفعاليات - قائمة الفعاليات', 'description' => 'تاريخ الفعالية الأولى'],
                            'events__list__item_01__title' => ['label' => 'الفعالية 1: العنوان', 'type' => 'text', 'group' => 'الفعاليات - قائمة الفعاليات', 'description' => 'عنوان الفعالية الأولى'],
                            'events__list__item_01__desc'  => ['label' => 'الفعالية 1: الوصف', 'type' => 'textarea', 'group' => 'الفعاليات - قائمة الفعاليات', 'description' => 'وصف الفعالية الأولى'],
                            'events__list__item_01__loc'   => ['label' => 'الفعالية 1: الموقع', 'type' => 'text', 'group' => 'الفعاليات - قائمة الفعاليات', 'description' => 'موقع الفعالية الأولى'],
                            'events__list__item_02__date'  => ['label' => 'الفعالية 2: التاريخ', 'type' => 'text', 'group' => 'الفعاليات - قائمة الفعاليات', 'description' => 'تاريخ الفعالية الثانية'],
                            'events__list__item_02__title' => ['label' => 'الفعالية 2: العنوان', 'type' => 'text', 'group' => 'الفعاليات - قائمة الفعاليات', 'description' => 'عنوان الفعالية الثانية'],
                            'events__list__item_02__desc'  => ['label' => 'الفعالية 2: الوصف', 'type' => 'textarea', 'group' => 'الفعاليات - قائمة الفعاليات', 'description' => 'وصف الفعالية الثانية'],
                            'events__list__item_02__loc'   => ['label' => 'الفعالية 2: الموقع', 'type' => 'text', 'group' => 'الفعاليات - قائمة الفعاليات', 'description' => 'موقع الفعالية الثانية'],
                            'events__list__item_03__date'  => ['label' => 'الفعالية 3: التاريخ', 'type' => 'text', 'group' => 'الفعاليات - قائمة الفعاليات', 'description' => 'تاريخ الفعالية الثالثة'],
                            'events__list__item_03__title' => ['label' => 'الفعالية 3: العنوان', 'type' => 'text', 'group' => 'الفعاليات - قائمة الفعاليات', 'description' => 'عنوان الفعالية الثالثة'],
                            'events__list__item_03__desc'  => ['label' => 'الفعالية 3: الوصف', 'type' => 'textarea', 'group' => 'الفعاليات - قائمة الفعاليات', 'description' => 'وصف الفعالية الثالثة'],
                            'events__list__item_03__loc'   => ['label' => 'الفعالية 3: الموقع', 'type' => 'text', 'group' => 'الفعاليات - قائمة الفعاليات', 'description' => 'موقع الفعالية الثالثة'],
                            'events__list__item_04__date'  => ['label' => 'الفعالية 4: التاريخ', 'type' => 'text', 'group' => 'الفعاليات - قائمة الفعاليات', 'description' => 'تاريخ الفعالية الرابعة'],
                            'events__list__item_04__title' => ['label' => 'الفعالية 4: العنوان', 'type' => 'text', 'group' => 'الفعاليات - قائمة الفعاليات', 'description' => 'عنوان الفعالية الرابعة'],
                            'events__list__item_04__desc'  => ['label' => 'الفعالية 4: الوصف', 'type' => 'textarea', 'group' => 'الفعاليات - قائمة الفعاليات', 'description' => 'وصف الفعالية الرابعة'],
                            'events__list__item_04__loc'   => ['label' => 'الفعالية 4: الموقع', 'type' => 'text', 'group' => 'الفعاليات - قائمة الفعاليات', 'description' => 'موقع الفعالية الرابعة'],
                            'events__list__item_05__date'  => ['label' => 'الفعالية 5: التاريخ', 'type' => 'text', 'group' => 'الفعاليات - قائمة الفعاليات', 'description' => 'تاريخ الفعالية الخامسة'],
                            'events__list__item_05__title' => ['label' => 'الفعالية 5: العنوان', 'type' => 'text', 'group' => 'الفعاليات - قائمة الفعاليات', 'description' => 'عنوان الفعالية الخامسة'],
                            'events__list__item_05__desc'  => ['label' => 'الفعالية 5: الوصف', 'type' => 'textarea', 'group' => 'الفعاليات - قائمة الفعاليات', 'description' => 'وصف الفعالية الخامسة'],
                            'events__list__item_05__loc'   => ['label' => 'الفعالية 5: الموقع', 'type' => 'text', 'group' => 'الفعاليات - قائمة الفعاليات', 'description' => 'موقع الفعالية الخامسة'],
                            'events__list__item_06__date'  => ['label' => 'الفعالية 6: التاريخ', 'type' => 'text', 'group' => 'الفعاليات - قائمة الفعاليات', 'description' => 'تاريخ الفعالية السادسة'],
                            'events__list__item_06__title' => ['label' => 'الفعالية 6: العنوان', 'type' => 'text', 'group' => 'الفعاليات - قائمة الفعاليات', 'description' => 'عنوان الفعالية السادسة'],
                            'events__list__item_06__desc'  => ['label' => 'الفعالية 6: الوصف', 'type' => 'textarea', 'group' => 'الفعاليات - قائمة الفعاليات', 'description' => 'وصف الفعالية السادسة'],
                            'events__list__item_06__loc'   => ['label' => 'الفعالية 6: الموقع', 'type' => 'text', 'group' => 'الفعاليات - قائمة الفعاليات', 'description' => 'موقع الفعالية السادسة'],
                        ]
                    ]
                ]
            ],
            'about' => [
                'label' => 'من نحن',
                'sections' => [
                    'hero' => [
                        'label' => 'قسم البطل',
                        'fields' => [
                            'about__hero__eyebrow' => ['label' => 'الوسم', 'type' => 'text', 'group' => 'من نحن - قسم البطل', 'description' => 'وسم صغير يظهر فوق العنوان الرئيسي'],
                            'about__hero__title'   => ['label' => 'العنوان', 'type' => 'text', 'group' => 'من نحن - قسم البطل', 'description' => 'العنوان الرئيسي لصفحة من نحن'],
                            'about__hero__desc'    => ['label' => 'الوصف', 'type' => 'textarea', 'group' => 'من نحن - قسم البطل', 'description' => 'الوصف الفرعي لصفحة من نحن'],
                        ]
                    ],
                    'story' => [
                        'label' => 'قصتنا',
                        'fields' => [
                            'about__story__heading' => ['label' => 'العنوان', 'type' => 'text', 'group' => 'من نحن - قصتنا', 'description' => 'عنوان قسم قصتنا'],
                            'about__story__p1'      => ['label' => 'الفقرة الأولى', 'type' => 'textarea', 'group' => 'من نحن - قصتنا', 'description' => 'الفقرة الأولى من قصة الجامعة'],
                            'about__story__p2'      => ['label' => 'الفقرة الثانية', 'type' => 'textarea', 'group' => 'من نحن - قصتنا', 'description' => 'الفقرة الثانية من قصة الجامعة'],
                        ]
                    ],
                    'vision' => [
                        'label' => 'الرؤية',
                        'fields' => [
                            'about__vision__label' => ['label' => 'تسمية الرؤية', 'type' => 'text', 'group' => 'من نحن - الرؤية', 'description' => 'تسمية قسم الرؤية'],
                            'about__vision__text'  => ['label' => 'نص الرؤية', 'type' => 'textarea', 'group' => 'من نحن - الرؤية', 'description' => 'نص رؤية الجامعة'],
                        ]
                    ],
                    'stats' => [
                        'label' => 'الإحصائيات',
                        'fields' => [
                            'about__stats__card__programs'          => ['label' => 'عدد البرامج', 'type' => 'text', 'group' => 'من نحن - الإحصائيات', 'description' => 'رقم عدد البرامج الأكاديمية'],
                            'about__stats__card__programs_lbl'      => ['label' => 'تسمية البرامج', 'type' => 'text', 'group' => 'من نحن - الإحصائيات', 'description' => 'تسمية بطاقة البرامج'],
                            'about__stats__card__partnerships'      => ['label' => 'عدد الشراكات', 'type' => 'text', 'group' => 'من نحن - الإحصائيات', 'description' => 'رقم عدد الشراكات الدولية'],
                            'about__stats__card__partnerships_lbl'  => ['label' => 'تسمية الشراكات', 'type' => 'text', 'group' => 'من نحن - الإحصائيات', 'description' => 'تسمية بطاقة الشراكات'],
                            'about__stats__card__graduates'         => ['label' => 'عدد الخريجين', 'type' => 'text', 'group' => 'من نحن - الإحصائيات', 'description' => 'رقم عدد الخريجين'],
                            'about__stats__card__graduates_lbl'     => ['label' => 'تسمية الخريجين', 'type' => 'text', 'group' => 'من نحن - الإحصائيات', 'description' => 'تسمية بطاقة الخريجين'],
                            'about__stats__card__accreditation'     => ['label' => 'نسبة الاعتماد', 'type' => 'text', 'group' => 'من نحن - الإحصائيات', 'description' => 'نسبة الاعتماد الأكاديمي'],
                            'about__stats__card__accreditation_lbl' => ['label' => 'تسمية الاعتماد', 'type' => 'text', 'group' => 'من نحن - الإحصائيات', 'description' => 'تسمية بطاقة الاعتماد'],
                        ]
                    ],
                    'team' => [
                        'label' => 'فريق القيادة',
                        'fields' => [
                            'about__team__heading'         => ['label' => 'عنوان القسم', 'type' => 'text', 'group' => 'من نحن - فريق القيادة', 'description' => 'عنوان قسم فريق القيادة'],
                            'about__team__member_01__name' => ['label' => 'العضو 1: الاسم', 'type' => 'text', 'group' => 'من نحن - فريق القيادة', 'description' => 'اسم العضو الأول'],
                            'about__team__member_01__role' => ['label' => 'العضو 1: المنصب', 'type' => 'text', 'group' => 'من نحن - فريق القيادة', 'description' => 'منصب العضو الأول'],
                            'about__team__member_01__bio'  => ['label' => 'العضو 1: السيرة', 'type' => 'textarea', 'group' => 'من نحن - فريق القيادة', 'description' => 'السيرة الذاتية للعضو الأول'],
                            'about__team__member_02__name' => ['label' => 'العضو 2: الاسم', 'type' => 'text', 'group' => 'من نحن - فريق القيادة', 'description' => 'اسم العضو الثاني'],
                            'about__team__member_02__role' => ['label' => 'العضو 2: المنصب', 'type' => 'text', 'group' => 'من نحن - فريق القيادة', 'description' => 'منصب العضو الثاني'],
                            'about__team__member_02__bio'  => ['label' => 'العضو 2: السيرة', 'type' => 'textarea', 'group' => 'من نحن - فريق القيادة', 'description' => 'السيرة الذاتية للعضو الثاني'],
                            'about__team__member_03__name' => ['label' => 'العضو 3: الاسم', 'type' => 'text', 'group' => 'من نحن - فريق القيادة', 'description' => 'اسم العضو الثالث'],
                            'about__team__member_03__role' => ['label' => 'العضو 3: المنصب', 'type' => 'text', 'group' => 'من نحن - فريق القيادة', 'description' => 'منصب العضو الثالث'],
                            'about__team__member_03__bio'  => ['label' => 'العضو 3: السيرة', 'type' => 'textarea', 'group' => 'من نحن - فريق القيادة', 'description' => 'السيرة الذاتية للعضو الثالث'],
                            'about__team__member_04__name' => ['label' => 'العضو 4: الاسم', 'type' => 'text', 'group' => 'من نحن - فريق القيادة', 'description' => 'اسم العضو الرابع'],
                            'about__team__member_04__role' => ['label' => 'العضو 4: المنصب', 'type' => 'text', 'group' => 'من نحن - فريق القيادة', 'description' => 'منصب العضو الرابع'],
                            'about__team__member_04__bio'  => ['label' => 'العضو 4: السيرة', 'type' => 'textarea', 'group' => 'من نحن - فريق القيادة', 'description' => 'السيرة الذاتية للعضو الرابع'],
                        ]
                    ]
                ]
            ],
            'global' => [
                'label' => 'عناصر عامة (Global)',
                'sections' => [
                    'navigation' => [
                        'label' => 'القائمة العلوية',
                        'fields' => [
                            'global__nav__brand_ar'   => ['label' => 'اسم الجامعة (عربي)', 'type' => 'text', 'group' => 'القائمة العلوية - الهوية', 'description' => 'اسم الجامعة باللغة العربية في القائمة العلوية'],
                            'global__nav__brand_en'   => ['label' => 'اسم الجامعة (إنجليزي)', 'type' => 'text', 'group' => 'القائمة العلوية - الهوية', 'description' => 'اسم الجامعة باللغة الإنجليزية في القائمة العلوية'],
                            'global__nav__verify_text'=> ['label' => 'نص التحقق', 'type' => 'text', 'group' => 'القائمة العلوية - الأزرار', 'description' => 'النص المعروض على زر التحقق من الشهادة'],
                            'global__nav__link_home'  => ['label' => 'رابط الرئيسية', 'type' => 'text', 'group' => 'القائمة العلوية - الروابط', 'description' => 'رابط صفحة الرئيسية في القائمة'],
                            'global__nav__link_programs' => ['label' => 'رابط البرامج', 'type' => 'text', 'group' => 'القائمة العلوية - الروابط', 'description' => 'رابط صفحة البرامج الأكاديمية في القائمة'],
                            'global__nav__link_events'   => ['label' => 'رابط الفعاليات', 'type' => 'text', 'group' => 'القائمة العلوية - الروابط', 'description' => 'رابط صفحة الفعاليات في القائمة'],
                            'global__nav__link_about'    => ['label' => 'رابط من نحن', 'type' => 'text', 'group' => 'القائمة العلوية - الروابط', 'description' => 'رابط صفحة من نحن في القائمة'],
                            'global__nav__link_admin'    => ['label' => 'رابط لوحة التحكم', 'type' => 'text', 'group' => 'القائمة العلوية - الروابط', 'description' => 'رابط بوابة الإدارة في القائمة'],
                        ]
                    ],
                    'footer' => [
                        'label' => 'التذييل (Footer)',
                        'fields' => [
                            'global__footer__brand_ar_1'         => ['label' => 'اسم الجامعة 1 (عربي)', 'type' => 'text', 'group' => 'التذييل - الهوية', 'description' => 'السطر الأول من اسم الجامعة بالعربية في التذييل'],
                            'global__footer__brand_ar_2'         => ['label' => 'اسم الجامعة 2 (عربي)', 'type' => 'text', 'group' => 'التذييل - الهوية', 'description' => 'السطر الثاني من اسم الجامعة بالعربية في التذييل'],
                            'global__footer__brand_en'           => ['label' => 'اسم الجامعة (إنجليزي)', 'type' => 'text', 'group' => 'التذييل - الهوية', 'description' => 'اسم الجامعة بالإنجليزية في التذييل'],
                            'global__footer__desc'              => ['label' => 'الوصف', 'type' => 'textarea', 'group' => 'التذييل - الهوية', 'description' => 'وصف مختصر للجامعة في التذييل'],
                            'global__footer__email'             => ['label' => 'البريد الإلكتروني', 'type' => 'text', 'group' => 'التذييل - التواصل', 'description' => 'عنوان البريد الإلكتروني للتواصل'],
                            'global__footer__whatsapp'           => ['label' => 'رقم الواتساب', 'type' => 'text', 'group' => 'التذييل - التواصل', 'description' => 'رقم الواتساب للتواصل السريع'],
                            'global__footer__col_programs_title' => ['label' => 'عنوان العمود: البرامج', 'type' => 'text', 'group' => 'التذييل - الأعمدة', 'description' => 'عنوان عمود البرامج الأكاديمية'],
                            'global__footer__col_quick_title'    => ['label' => 'عنوان العمود: روابط سريعة', 'type' => 'text', 'group' => 'التذييل - الأعمدة', 'description' => 'عنوان عمود الروابط السريعة'],
                            'global__footer__col_contact_title'  => ['label' => 'عنوان العمود: تواصل معنا', 'type' => 'text', 'group' => 'التذييل - الأعمدة', 'description' => 'عنوان عمود معلومات التواصل'],
                            'global__footer__copyright'         => ['label' => 'حقوق النشر', 'type' => 'text', 'group' => 'التذييل - الحقوق', 'description' => 'نص حقوق النشر في أسفل الصفحة'],
                            'global__footer__social_facebook'   => ['label' => 'رابط فيسبوك', 'type' => 'text', 'group' => 'التذييل - السوشيال ميديا', 'description' => 'رابط صفحة فيسبوك الرسمية'],
                            'global__footer__social_twitter'    => ['label' => 'رابط تويتر', 'type' => 'text', 'group' => 'التذييل - السوشيال ميديا', 'description' => 'رابط حساب تويتر الرسمي'],
                            'global__footer__social_linkedin'   => ['label' => 'رابط لينكد إن', 'type' => 'text', 'group' => 'التذييل - السوشيال ميديا', 'description' => 'رابط صفحة لينكد إن الرسمية'],
                            'global__footer__social_youtube'    => ['label' => 'رابط يوتيوب', 'type' => 'text', 'group' => 'التذييل - السوشيال ميديا', 'description' => 'رابط قناة يوتيوب الرسمية'],
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * Get default values from JSON
     */
    public static function get_defaults() {
        if ( ! file_exists( self::$map_file ) ) return [];
        $content = file_get_contents( self::$map_file );
        return json_decode( $content, true ) ?: [];
    }
}
