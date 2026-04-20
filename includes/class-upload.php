<?php
/**
 * AUSR CMS - Upload Class
 * مسؤولة عن رفع الصور والملفات بأمان
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class AUSR_Upload {

    private static $allowed_image_types = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
    ];

    private static $allowed_doc_types = [
        'application/pdf' => 'pdf',
    ];

    private static $max_size = 5242880; // 5MB

    // ============================================================
    // رفع ملف
    // ============================================================
    public static function upload_file( $file ) {
        // التحقق من وجود الملف
        if ( empty( $file ) || $file['error'] !== UPLOAD_ERR_OK ) {
            return [
                'success' => false,
                'message' => 'لم يتم اختيار ملف أو حدث خطأ في الرفع',
                'code'    => 400,
            ];
        }

        // التحقق من الحجم
        if ( $file['size'] > self::$max_size ) {
            return [
                'success' => false,
                'message' => 'حجم الملف كبير جداً (الحد الأقصى 5MB)',
                'code'    => 400,
            ];
        }

        // التحقق من نوع الملف (MIME Type الحقيقي)
        $mime_type = self::get_real_mime_type( $file['tmp_name'] );
        $all_allowed = array_merge( self::$allowed_image_types, self::$allowed_doc_types );

        if ( ! array_key_exists( $mime_type, $all_allowed ) ) {
            return [
                'success' => false,
                'message' => 'نوع الملف غير مسموح. الأنواع المقبولة: JPG, PNG, WebP, GIF, PDF',
                'code'    => 400,
            ];
        }

        // رفع عبر WordPress Media Library
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';

        // تنظيف اسم الملف
        $file['name'] = self::sanitize_filename( $file['name'] );

        $upload = wp_handle_upload( $file, [ 'test_form' => false ] );

        if ( isset( $upload['error'] ) ) {
            return [
                'success' => false,
                'message' => $upload['error'],
                'code'    => 500,
            ];
        }

        // إضافة للمكتبة
        $attachment_id = wp_insert_attachment(
            [
                'post_mime_type' => $mime_type,
                'post_title'     => sanitize_file_name( pathinfo( $file['name'], PATHINFO_FILENAME ) ),
                'post_status'    => 'inherit',
            ],
            $upload['file']
        );

        if ( is_wp_error( $attachment_id ) ) {
            return [
                'success' => false,
                'message' => 'فشل في حفظ الملف',
                'code'    => 500,
            ];
        }

        // توليد thumbnails للصور
        if ( array_key_exists( $mime_type, self::$allowed_image_types ) ) {
            $metadata = wp_generate_attachment_metadata( $attachment_id, $upload['file'] );
            wp_update_attachment_metadata( $attachment_id, $metadata );
        }

        // يُستخدم مع تدقيق الحذف (Double Check) في REST — لا يُحذف إلا مرفق تم رفعه عبر AUSR
        update_post_meta( $attachment_id, '_ausr_cms_upload', '1' );

        AUSR_Database::log( 'file_uploaded', $file['name'], null, $upload['url'] );

        return [
            'success'       => true,
            'url'           => $upload['url'],
            'attachment_id' => $attachment_id,
            'type'          => $mime_type,
            'size'          => $file['size'],
            'name'          => $file['name'],
            'message'       => 'تم رفع الملف بنجاح ✅',
        ];
    }

    // ============================================================
    // جلب MIME Type الحقيقي
    // ============================================================
    private static function get_real_mime_type( $file_path ) {
        if ( function_exists( 'finfo_open' ) ) {
            $finfo = finfo_open( FILEINFO_MIME_TYPE );
            $mime  = finfo_file( $finfo, $file_path );
            finfo_close( $finfo );
            return $mime;
        }
        // Fallback
        return mime_content_type( $file_path );
    }

    // ============================================================
    // تنظيف اسم الملف
    // ============================================================
    private static function sanitize_filename( $filename ) {
        // إزالة الأحرف الخطرة
        $filename = preg_replace( '/[^a-zA-Z0-9._-]/', '-', $filename );
        // إزالة النقاط المتعددة (منع double extension)
        $filename = preg_replace( '/\.+/', '.', $filename );
        // إضافة timestamp لمنع التكرار
        $info = pathinfo( $filename );
        return $info['filename'] . '_' . time() . '.' . $info['extension'];
    }
}
