jQuery(document).ready(function($) {
    
    var timer; // ตัวจับเวลา (Debounce)

    // ฟังก์ชันส่งข้อมูลไปอัปเดต (AJAX)
    function updateCartAjax($input) {
        var qty = $input.val();
        var itemKey = $input.attr('name').match(/cart\[(.*?)\]/)[1]; // แกะ Key ออกมาจากชื่อ input
        var $row = $input.closest('.cart-item-card'); // หาแถวสินค้าของตัวนี้

        // ใส่ Effect จางๆ ให้รู้ว่ากำลังโหลด
        $('.gustabe-cart-totals, .reward-progress-container').css('opacity', '0.5');
        $row.find('.item-subtotal').css('opacity', '0.5');

        $.ajax({
            url: gustabe_ajax.url,
            type: 'POST',
            data: {
                action: 'gustabe_update_cart_qty',
                nonce: gustabe_ajax.nonce,
                key: itemKey,
                qty: qty
            },
            success: function(res) {
                if (res.success) {
                    // 1. อัปเดตกล่องราคารวม (ขวามือ)
                    $('.gustabe-cart-totals').html(res.data.cart_totals);
                    
                    // 2. อัปเดตราคาของสินค้านั้นๆ (Item Subtotal)
                    if (res.data.item_subtotal) {
                        $row.find('.item-subtotal').html(res.data.item_subtotal);
                    }

                    // 3. (แถม) ถ้าจำนวนเป็น 0 ให้ลบแถวนั้นทิ้ง
                    if (qty <= 0) {
                        $row.fadeOut(300, function(){ $(this).remove(); });
                    }

                    // 4. รีเฟรชหน้าถ้าจำเป็น (เช่น Reward เปลี่ยน Level)
                    // window.location.reload(); // ถ้าไม่อยากรีเฟรช ให้ปิดบรรทัดนี้ไว้
                }
            },
            complete: function() {
                // เอา Effect จางๆ ออก
                $('.gustabe-cart-totals, .reward-progress-container').css('opacity', '1');
                $row.find('.item-subtotal').css('opacity', '1');
                
                // Trigger event ให้ระบบอื่นรู้ (เช่น Mini Cart)
                $(document.body).trigger('wc_fragment_refresh');
            }
        });
    }

    // --- ปุ่มลดจำนวน (-) ---
    $(document).on('click', '.qty-btn.minus', function(e) {
        e.preventDefault();
        var $stepper = $(this).closest('.qty-stepper');
        var $input = $stepper.find('input.qty');
        var val = parseFloat($input.val());
        var step = parseFloat($input.attr('step')) || 1;

        if (val > 0) { // ยอมให้ลดเหลือ 0 ได้ (เพื่อลบ)
            val -= step;
            $input.val(val).trigger('change');
        }
    });

    // --- ปุ่มเพิ่มจำนวน (+) ---
    $(document).on('click', '.qty-btn.plus', function(e) {
        e.preventDefault();
        var $stepper = $(this).closest('.qty-stepper');
        var $input = $stepper.find('input.qty');
        var val = parseFloat($input.val());
        var max = parseFloat($input.attr('max')) || 9999;
        var step = parseFloat($input.attr('step')) || 1;

        if (val < max) {
            val += step;
            $input.val(val).trigger('change');
        }
    });

    // --- ดักจับเมื่อตัวเลขเปลี่ยน (Change Event) ---
    $(document).on('change', 'input.qty', function() {
        var $input = $(this);
        
        // ใช้ Timer หน่วงเวลา 0.5 วิ (กันคนกดรัวๆ แล้ว Server พัง)
        clearTimeout(timer);
        timer = setTimeout(function() {
            updateCartAjax($input);
        }, 500); 
    });

});