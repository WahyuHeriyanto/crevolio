<x-public-layout>

<div class="min-h-screen bg-[#F8F9FB] py-20">
    <div class="max-w-4xl mx-auto px-6">
        <div class="bg-white rounded-[40px] p-8 md:p-16 shadow-sm border border-gray-100">
            <h1 class="text-4xl font-black text-gray-900 mb-2">Terms of Service</h1>
            <p class="text-gray-500 mb-10">Last updated: 30 December 2025</p>

            <div class="prose prose-indigo max-w-none text-gray-600 space-y-8">

                {{-- Introduction --}}
                <section>
                    <h2 class="text-xl font-bold text-gray-900 mb-4">1. Introduction</h2>
                    <p>
                        Welcome to Crevolio. These Terms of Service ("Terms") govern your access to and use of the Crevolio website and platform.
                        By accessing or using our services, you agree to be bound by these Terms.
                    </p>
                </section>

                {{-- Eligibility --}}
                <section>
                    <h2 class="text-xl font-bold text-gray-900 mb-4">2. Eligibility</h2>
                    <p>
                        You must be at least 13 years old to use Crevolio. By using this platform, you confirm that you are a real individual
                        and not an automated system or bot.
                    </p>
                </section>

                {{-- Account & Authentication --}}
                <section>
                    <h2 class="text-xl font-bold text-gray-900 mb-4">3. Accounts and Authentication</h2>
                    <p>
                        You may sign in to Crevolio using third-party authentication providers such as Google.
                        By signing in, you allow us to access basic profile information such as your name, email address,
                        and profile photo for authentication and account management purposes.
                    </p>
                    <p>
                        You are responsible for maintaining the security of your account and for all activities that occur under it.
                    </p>
                </section>

                {{-- Acceptable Use --}}
                <section>
                    <h2 class="text-xl font-bold text-gray-900 mb-4">4. Acceptable Use</h2>
                    <p>You agree not to:</p>
                    <ul class="list-disc pl-6 space-y-2">
                        <li>Use the platform for unlawful or fraudulent purposes.</li>
                        <li>Impersonate another person or misrepresent your identity.</li>
                        <li>Attempt to access or disrupt the platform’s systems or security.</li>
                        <li>Use automated scripts, bots, or scrapers without authorization.</li>
                    </ul>
                </section>

                {{-- Data & Privacy --}}
                <section>
                    <h2 class="text-xl font-bold text-gray-900 mb-4">5. Data and Privacy</h2>
                    <p>
                        Your privacy is important to us. Our collection and use of personal data are governed by our
                        <a href="{{ url('/privacy-policy') }}">Privacy Policy</a>.
                        By using Crevolio, you consent to the collection and use of your data as described there.
                    </p>
                </section>

                {{-- Termination --}}
                <section>
                    <h2 class="text-xl font-bold text-gray-900 mb-4">6. Account Termination</h2>
                    <p>
                        We reserve the right to suspend or terminate your account if you violate these Terms or
                        engage in activities that may harm the integrity of the platform or its community.
                    </p>
                </section>

                {{-- Disclaimer --}}
                <section>
                    <h2 class="text-xl font-bold text-gray-900 mb-4">7. Disclaimer</h2>
                    <p>
                        Crevolio is provided on an "as is" and "as available" basis. We do not guarantee that the service
                        will be uninterrupted, secure, or error-free.
                    </p>
                </section>

                {{-- Changes --}}
                <section>
                    <h2 class="text-xl font-bold text-gray-900 mb-4">8. Changes to These Terms</h2>
                    <p>
                        We may update these Terms from time to time. Any changes will be posted on this page,
                        and continued use of the platform constitutes acceptance of the updated Terms.
                    </p>
                </section>

                {{-- Contact --}}
                <section class="pt-10 border-t border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">9. Contact Information</h2>
                    <p>If you have any questions about these Terms, please contact us at:</p>
                    <div class="mt-4 p-6 bg-gray-50 rounded-2xl border border-gray-100 inline-block">
                        <p class="font-bold text-gray-900">Crevolio Developer</p>
                        <p class="text-indigo-600">wahyu15heriyanto@gmail.com</p>
                    </div>
                </section>

            </div>
        </div>
    </div>
</div>

</x-public-layout>
