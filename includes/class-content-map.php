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
                            'home-hero-badge'   => ['label' => 'شارة الترحيب', 'type' => 'text'],
                            'home-hero-title-1' => ['label' => 'العنوان (سطر 1)', 'type' => 'text'],
                            'home-hero-title-2' => ['label' => 'العنوان (سطر 2)', 'type' => 'text'],
                            'home-hero-title-3' => ['label' => 'العنوان (سطر 3)', 'type' => 'text'],
                            'home-hero-sub'     => ['label' => 'الوصف الفرعي', 'type' => 'textarea'],
                        ]
                    ],
                    'stats' => [
                        'label' => 'الإحصائيات',
                        'fields' => [
                            'home-stat-programs'    => ['label' => 'عدد البرامج', 'type' => 'text'],
                            'home-stat-programs-lbl' => ['label' => 'تسمية البرامج', 'type' => 'text'],
                            'home-stat-partnerships' => ['label' => 'عدد الشراكات', 'type' => 'text'],
                            'home-stat-partnerships-lbl' => ['label' => 'تسمية الشراكات', 'type' => 'text'],
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
                            'prog-hero-title' => ['label' => 'عنوان الصفحة', 'type' => 'text'],
                            'prog-hero-desc'  => ['label' => 'وصف الصفحة', 'type' => 'textarea'],
                        ]
                    ]
                ]
            ],
            'events' => [
                'label' => 'الفعاليات',
                'sections' => [
                    'featured' => [
                        'label' => 'الفعالية المميزة',
                        'fields' => [
                            'ev-featured-title' => ['label' => 'عنوان الفعالية', 'type' => 'text'],
                            'ev-featured-desc'  => ['label' => 'وصف الفعالية', 'type' => 'textarea'],
                        ]
                    ]
                ]
            ],
            'about' => [
                'label' => 'من نحن',
                'sections' => [
                    'story' => [
                        'label' => 'قصتنا',
                        'fields' => [
                            'about-story-heading' => ['label' => 'العنوان', 'type' => 'text'],
                            'about-story-p1'      => ['label' => 'الفقرة الأولى', 'type' => 'textarea'],
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
