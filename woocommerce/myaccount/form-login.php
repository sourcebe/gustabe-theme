<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

do_action( 'woocommerce_before_customer_login_form' ); ?>

<style>
/* Make the main woocommerce wrapper a flex container on desktop to separate notices (left) and forms (right) */
.woocommerce {
	display: flex;
	flex-direction: column;
	gap: 2rem;
}
@media (min-width: 1024px) {
	.woocommerce {
		flex-direction: row;
		justify-content: space-between;
		align-items: flex-start;
	}
	.woocommerce-notices-wrapper {
		flex: 1;
		padding-right: 2rem;
		position: sticky;
		top: 2rem;
	}
	.woocommerce-notices-wrapper:empty {
		display: none;
	}
}

/* Style the WooCommerce error/message boxes for the login page to look like a giant terminal warning */
.woocommerce-error, .woocommerce-info, .woocommerce-message {
	background-color: rgba(15, 23, 42, 0.4) !important;
	border: 1px solid #1e293b !important;
	border-radius: 1rem !important;
	padding: 4rem 2rem !important;
	display: flex !important;
	flex-direction: column !important;
	align-items: center !important;
	justify-content: center !important;
	text-align: center !important;
	backdrop-filter: blur(24px) !important;
	margin: 0 !important;
	list-style: none !important;
	box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1) !important;
}
.woocommerce-error li, .woocommerce-info li, .woocommerce-message li {
	display: flex !important;
	flex-direction: column !important;
	align-items: center !important;
	gap: 1.5rem !important;
	color: #f87171 !important; /* Red for errors */
	font-family: monospace;
	font-size: 1.125rem;
}
.woocommerce-info li, .woocommerce-message li {
	color: #34d399 !important; /* Green for success/info */
}

/* Inject SVG icons using pseudo-elements */
.woocommerce-error li::before {
	content: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 24 24" fill="none" stroke="%23f87171" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>');
	display: block;
	margin-bottom: 1rem;
	opacity: 0.8;
}
.woocommerce-info li::before, .woocommerce-message li::before {
	content: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 24 24" fill="none" stroke="%2334d399" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9 12l2 2 4-4"/></svg>');
	display: block;
	margin-bottom: 1rem;
	opacity: 0.8;
}

/* Hide default WooCommerce icons if any */
.woocommerce-error::before, .woocommerce-info::before, .woocommerce-message::before {
	display: none !important;
}

/* Fix browser autofill background color to stay transparent */
input:-webkit-autofill,
input:-webkit-autofill:hover, 
input:-webkit-autofill:focus, 
input:-webkit-autofill:active {
    transition: background-color 5000s ease-in-out 0s !important;
    -webkit-text-fill-color: #34d399 !important; /* Emerald for login */
}
.woocommerce-form-register input:-webkit-autofill,
.woocommerce-form-register input:-webkit-autofill:hover, 
.woocommerce-form-register input:-webkit-autofill:focus, 
.woocommerce-form-register input:-webkit-autofill:active {
    -webkit-text-fill-color: #60a5fa !important; /* Blue for register */
}
</style>

<!-- Geek Terminal (Hidden by default, shown via JS if no errors) -->
<div id="hacker-terminal" class="hidden lg:flex flex-1 flex-col bg-slate-900/40 border border-slate-800 rounded-2xl shadow-xl backdrop-blur-xl h-[640px] overflow-hidden mr-8 relative self-start sticky top-8">
	<!-- Window Controls -->
	<div class="absolute top-4 right-4 flex gap-2 opacity-40">
		<div class="w-3 h-3 border border-slate-500 rounded-sm"></div>
		<div class="w-3 h-3 border border-slate-500 rounded-sm"></div>
		<div class="w-3 h-3 border border-slate-500 rounded-sm"></div>
	</div>
	<!-- Terminal Body -->
	<div class="mt-12 p-6 font-mono text-[13px] leading-relaxed text-emerald-400 w-full flex-1 overflow-hidden break-all whitespace-pre-wrap" id="terminal-output">
		<!-- JS Types here -->
	</div>
</div>

<div class="w-full lg:max-w-lg ml-auto flex flex-col gap-8" id="login-forms-container">
<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>
<div class="u-columns col2-set flex flex-col gap-8" id="customer_login">
	<div class="u-column1 col-1 bg-slate-900/40 border border-slate-800 rounded-2xl p-6 lg:p-10 shadow-xl backdrop-blur-xl">
<?php else : ?>
	<div class="bg-slate-900/40 border border-slate-800 rounded-2xl p-6 lg:p-10 shadow-xl backdrop-blur-xl">
<?php endif; ?>

		<h2 class="text-2xl md:text-3xl font-bold text-white mb-6 flex items-center gap-3">
			<i class="huge huge-login-01 text-emerald-400"></i>
			<?php esc_html_e( 'Login', 'woocommerce' ); ?>
		</h2>

		<form class="woocommerce-form woocommerce-form-login login flex flex-col gap-6" method="post" novalidate>

			<?php do_action( 'woocommerce_login_form_start' ); ?>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide m-0 flex flex-col md:flex-row md:items-end gap-2 md:gap-4">
				<label for="username" class="text-sm font-mono text-slate-400 whitespace-nowrap"><?php esc_html_e( 'Username or email address', 'woocommerce' ); ?>&nbsp;<span class="text-emerald-500" aria-hidden="true">*</span> :</label>
				<input type="text" style="background:transparent; border:none; border-bottom:1px solid #64748b; border-radius:0; padding:0 0 4px 0; box-shadow:none; outline:none;" class="woocommerce-Input woocommerce-Input--text input-text flex-1 font-mono text-emerald-400 focus:!border-emerald-500 focus:!ring-0 w-full" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) && is_string( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required aria-required="true" /><?php // @codingStandardsIgnoreLine ?>
			</p>
			
			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide m-0 flex flex-col md:flex-row md:items-end gap-2 md:gap-4">
				<label for="password" class="text-sm font-mono text-slate-400 whitespace-nowrap"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="text-emerald-500" aria-hidden="true">*</span> :</label>
				<input type="password" style="background:transparent; border:none; border-bottom:1px solid #64748b; border-radius:0; padding:0 0 4px 0; box-shadow:none; outline:none;" class="woocommerce-Input woocommerce-Input--text input-text flex-1 font-mono text-emerald-400 focus:!border-emerald-500 focus:!ring-0 w-full" name="password" id="password" autocomplete="current-password" required aria-required="true" />
			</p>

			<?php do_action( 'woocommerce_login_form' ); ?>

			<div class="flex items-center justify-between mt-2">
				<style>
					input#rememberme:checked ~ .check-off { display: none !important; }
					input#rememberme:checked ~ .check-on { display: inline !important; }
					input#rememberme:not(:checked) ~ .check-off { display: inline !important; }
					input#rememberme:not(:checked) ~ .check-on { display: none !important; }
				</style>
				<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme flex items-center gap-2 cursor-pointer group m-0 text-sm font-mono text-slate-400 hover:text-slate-200 transition-colors">
					<input class="woocommerce-form__input woocommerce-form__input-checkbox sr-only" name="rememberme" type="checkbox" id="rememberme" value="forever" />
					<span class="check-off">[ ]</span>
					<span class="check-on text-emerald-400" style="display:none;">[x]</span>
					<span><?php esc_html_e( 'Remember me', 'woocommerce' ); ?></span>
				</label>
				<p class="woocommerce-LostPassword lost_password m-0 text-sm font-mono">
					<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" class="text-slate-400 hover:text-emerald-400 transition-colors no-underline"><?php esc_html_e( 'Lost your password?', 'woocommerce' ); ?></a>
				</p>
			</div>
			
			<p class="form-row m-0 mt-4 text-center">
				<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
				<button type="submit" class="woocommerce-button button font-mono w-full md:w-auto px-12 !bg-transparent !border !border-emerald-500 !text-emerald-400 hover:!bg-emerald-500/10 !shadow-none woocommerce-form-login__submit<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="login" value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>">{ <?php esc_html_e( 'Log in', 'woocommerce' ); ?> }</button>
			</p>

			<?php do_action( 'woocommerce_login_form_end' ); ?>

		</form>

<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

	</div>

	<div class="u-column2 col-2 bg-slate-900/40 border border-slate-800 rounded-2xl p-6 lg:p-10 shadow-xl backdrop-blur-xl">

		<h2 class="text-2xl md:text-3xl font-bold text-white mb-6 flex items-center gap-3">
			<i class="huge huge-login-01 text-emerald-400"></i>
			<?php esc_html_e( 'Register', 'woocommerce' ); ?>
		</h2>

		<form method="post" class="woocommerce-form woocommerce-form-register register flex flex-col gap-6" <?php do_action( 'woocommerce_register_form_tag' ); ?> >

			<?php do_action( 'woocommerce_register_form_start' ); ?>

			<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide m-0 flex flex-col md:flex-row md:items-end gap-2 md:gap-4">
					<label for="reg_username" class="text-sm font-mono text-slate-400 whitespace-nowrap"><?php esc_html_e( 'Username', 'woocommerce' ); ?>&nbsp;<span class="text-blue-500" aria-hidden="true">*</span> :</label>
					<input type="text" style="background:transparent; border:none; border-bottom:1px solid #64748b; border-radius:0; padding:0 0 4px 0; box-shadow:none; outline:none;" class="woocommerce-Input woocommerce-Input--text input-text flex-1 font-mono text-blue-400 focus:!border-blue-500 focus:!ring-0 w-full" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required aria-required="true" /><?php // @codingStandardsIgnoreLine ?>
				</p>

			<?php endif; ?>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide m-0 flex flex-col md:flex-row md:items-end gap-2 md:gap-4">
				<label for="reg_email" class="text-sm font-mono text-slate-400 whitespace-nowrap"><?php esc_html_e( 'Email address', 'woocommerce' ); ?>&nbsp;<span class="text-blue-500" aria-hidden="true">*</span> :</label>
				<input type="email" style="background:transparent; border:none; border-bottom:1px solid #64748b; border-radius:0; padding:0 0 4px 0; box-shadow:none; outline:none;" class="woocommerce-Input woocommerce-Input--text input-text flex-1 font-mono text-blue-400 focus:!border-blue-500 focus:!ring-0 w-full" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" required aria-required="true" /><?php // @codingStandardsIgnoreLine ?>
			</p>

			<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide m-0 flex flex-col md:flex-row md:items-end gap-2 md:gap-4">
					<label for="reg_password" class="text-sm font-mono text-slate-400 whitespace-nowrap"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="text-blue-500" aria-hidden="true">*</span> :</label>
					<input type="password" style="background:transparent; border:none; border-bottom:1px solid #64748b; border-radius:0; padding:0 0 4px 0; box-shadow:none; outline:none;" class="woocommerce-Input woocommerce-Input--text input-text flex-1 font-mono text-blue-400 focus:!border-blue-500 focus:!ring-0 w-full" name="password" id="reg_password" autocomplete="new-password" required aria-required="true" />
				</p>

			<?php else : ?>

				<p class="font-mono text-sm text-slate-400 m-0 mt-4 text-center"><?php esc_html_e( 'A link to set a new password will be sent to your email address.', 'woocommerce' ); ?></p>

			<?php endif; ?>

			<?php do_action( 'woocommerce_register_form' ); ?>

			<p class="woocommerce-form-row form-row m-0 mt-4 text-center">
				<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
				<button type="submit" class="woocommerce-Button woocommerce-button button font-mono w-full md:w-auto px-12 !bg-transparent !border !border-blue-500 !text-blue-400 hover:!bg-blue-500/10 !shadow-none <?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?> woocommerce-form-register__submit" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>">{ <?php esc_html_e( 'Register', 'woocommerce' ); ?> }</button>
			</p>

			<?php do_action( 'woocommerce_register_form_end' ); ?>

		</form>

	</div>

</div>
<?php endif; ?>
</div>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>

<script>
(function() {
    const noticesWrapper = document.querySelector('.woocommerce-notices-wrapper');
    const terminal = document.getElementById('hacker-terminal');
    
    // Check if there are any errors or notices printed
    const hasNotices = noticesWrapper && noticesWrapper.innerHTML.trim() !== '';
    
    if (hasNotices) {
        // Hide terminal if there's an error
        if (terminal) terminal.style.display = 'none';
        if (noticesWrapper) noticesWrapper.style.display = 'block';
    } else {
        // Show terminal on desktop if no errors (let Tailwind hidden lg:flex do the work)
        if (terminal) terminal.style.display = '';
        if (noticesWrapper) noticesWrapper.style.display = 'none';
        
        // Start auto-typing code
        const terminalOutput = document.getElementById('terminal-output');
        if (!terminalOutput) return;

        const snippets = [
            `// Node.js: Advanced Fibonacci via Y-Combinator\nconst Y = f => (x => x(x))(y => f(x => y(y)(x)));\nconst fib = Y(f => n => (n <= 1 ? n : f(n - 1) + f(n - 2)));\n\nconst stream = Array.from({ length: 12 }, (_, i) => fib(i));\nconsole.log('[*] Sequence computed via fixed-point combinator:');\nconsole.log(stream.join(' -> '));\nconsole.log('[+] Process terminated successfully.');`,
            `/* C++: Compile-Time Factorial via Template Metaprogramming */\n#include <iostream>\n\ntemplate<unsigned int n>\nstruct Factorial {\n    enum { value = n * Factorial<n - 1>::value };\n};\ntemplate<>\nstruct Factorial<0> {\n    enum { value = 1 };\n};\n\nint main() {\n    constexpr auto result = Factorial<10>::value;\n    std::cout << "[*] Compile-time calculation (10!) = " << result << std::endl;\n    return 0;\n}`,
            `# Rust: Prime Sieve via Advanced Iterators\nfn main() {\n    let limit = 50;\n    let primes: Vec<usize> = (2..limit)\n        .filter(|&x| (2..=(x as f64).sqrt() as usize).all(|i| x % i != 0))\n        .collect();\n    \n    println!("[*] Computed primes up to {}:", limit);\n    println!("{:?}", primes);\n    println!("[+] Safe memory boundaries verified.");\n}`,
            `# Python: Quicksort via One-Liner Lambda & Comprehensions\nquicksort = lambda l: quicksort([x for x in l[1:] if x <= l[0]]) + [l[0]] + quicksort([x for x in l[1:] if x > l[0]]) if l else []\n\ndata_stream = [3, 1, 4, 1, 5, 9, 2, 6, 5]\nsorted_data = quicksort(data_stream)\n\nprint(f"[*] Entropy reduced.")\nprint(f"[+] Sorted deterministic stream: {sorted_data}")`,
            `// Go: Concurrent Array Summation via Goroutines & Channels\npackage main\nimport "fmt"\n\nfunc sum(s []int, c chan int) {\n    total := 0\n    for _, v := range s { total += v }\n    c <- total\n}\n\nfunc main() {\n    s := []int{7, 2, 8, -9, 4, 0, 1337}\n    c := make(chan int)\n    \n    go sum(s[:len(s)/2], c)\n    go sum(s[len(s)/2:], c)\n    x, y := <-c, <-c\n    \n    fmt.Printf("[+] Parallel execution finished.\\n[*] Final Sum: %d\\n", x+y)\n}`,
            `<\x3Fphp\n// PHP: Matrix Summation via Functional Reduction\n$matrix = [[1, 2], [3, 4], [5, 6]];\n$flatten = fn($arr) => array_merge(...$arr);\n\n$sum = array_reduce($flatten($matrix), fn($c, $i) => $c + $i, 0);\n\necho "[+] Tensor collapsed successfully.\\n";\necho "[*] Total scalar magnitude: " . (string)($sum ^ 0x00) . "\\n";\n?>`
        ];

        let currentSnippet = 0;
        let charIndex = 0;
        
        function typeCode() {
            if (currentSnippet >= snippets.length) {
                currentSnippet = 0;
            }
            
            let text = snippets[currentSnippet];
            
            if (charIndex < text.length) {
                // Add character
                terminalOutput.textContent = text.substring(0, charIndex + 1);
                charIndex++;
                
                // Random typing speed (10ms - 40ms)
                setTimeout(typeCode, Math.random() * 30 + 10);
            } else {
                // Wait 2 seconds, then clear and start next
                setTimeout(() => {
                    charIndex = 0;
                    currentSnippet++;
                    terminalOutput.textContent = '';
                    typeCode();
                }, 2000);
            }
        }
        
        // Start typing
        setTimeout(typeCode, 500);
    }
})();
</script>
