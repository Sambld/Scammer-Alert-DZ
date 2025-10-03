import { Link } from '@inertiajs/react';
import { type PropsWithChildren } from 'react';
import { Shield, ArrowLeft } from 'lucide-react';
import { LanguageSwitcher } from '@/components/LanguageSwitcher';
import { ThemeToggle } from '@/components/theme-toggle';

interface AuthLayoutProps {
    name?: string;
    title?: string;
    description?: string;
}

export default function AuthSimpleLayout({ children, title, description }: PropsWithChildren<AuthLayoutProps>) {
    return (
        <div className="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 relative overflow-hidden">
            {/* Background decorations */}
            <div className="absolute inset-0">
                {/* Animated background elements */}
                <div className="absolute top-0 left-0 w-full h-full">
                    {[...Array(4)].map((_, i) => (
                        <div
                            key={i}
                            className="absolute w-64 h-64 bg-gradient-to-r from-red-200/20 to-orange-200/20 dark:from-red-800/20 dark:to-orange-800/20 rounded-full blur-3xl animate-pulse"
                            style={{
                                left: `${10 + i * 25}%`,
                                top: `${5 + i * 20}%`,
                                animationDelay: `${i * 3}s`,
                                animationDuration: `${6 + i}s`
                            }}
                        />
                    ))}
                </div>
                
                {/* Grid pattern */}
                <div className="absolute inset-0 opacity-5">
                    <div className="absolute inset-0" style={{
                        backgroundImage: `url("data:image/svg+xml,%3csvg width='60' height='60' xmlns='http://www.w3.org/2000/svg'%3e%3cdefs%3e%3cpattern id='auth-grid' width='60' height='60' patternUnits='userSpaceOnUse'%3e%3cpath d='m 60 0 l 0 60 l -60 0 l 0 -60 z' fill='none' stroke='%23000000' stroke-width='1'/%3e%3c/pattern%3e%3c/defs%3e%3crect width='100%25' height='100%25' fill='url(%23auth-grid)' /%3e%3c/svg%3e")`,
                    }}></div>
                </div>
            </div>

            {/* Header */}
            <header className="relative z-50 w-full border-b border-slate-200/50 bg-white/80 backdrop-blur-md dark:border-slate-700/50 dark:bg-slate-900/80">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <nav className="flex h-16 items-center justify-between">
                        <div className="flex items-center space-x-3">
                            <Link href={route('home')} className="flex items-center space-x-3 group">
                                <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-red-600 text-white font-bold text-lg group-hover:scale-105 transition-transform">
                                    E
                                </div>
                                <span className="text-xl font-bold text-slate-900 dark:text-white">
                                    EscrocAlert
                                </span>
                            </Link>
                        </div>
                        
                        <div className="flex items-center gap-4">
                            <ThemeToggle />
                            <LanguageSwitcher />
                        </div>
                    </nav>
                </div>
            </header>

            {/* Main content */}
            <div className="relative flex min-h-[calc(100vh-4rem)] flex-col items-center justify-center gap-6 p-6 md:p-10">
                <div className="w-full max-w-md">
                    {/* Back to home link */}
                    <div className="mb-8">
                        <Link 
                            href={route('home')} 
                            className="inline-flex items-center text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors group"
                        >
                            <ArrowLeft className="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" />
                            Back to Home
                        </Link>
                    </div>

                    {/* Auth card */}
                    <div className="bg-white/90 dark:bg-slate-800/90 backdrop-blur-sm rounded-3xl border border-slate-200/50 dark:border-slate-700/50 shadow-2xl hover:shadow-3xl transition-all duration-300 p-8 lg:p-10">
                        {/* Header section */}
                        <div className="flex flex-col items-center gap-6 mb-8">
                            {/* Logo and brand */}
                            <div className="flex flex-col items-center gap-3">
                                <div className="flex h-16 w-16 items-center justify-center rounded-2xl bg-red-600 text-white shadow-lg">
                                    <Shield className="w-8 h-8" />
                                </div>
                                <div className="text-center">
                                    <h1 className="text-2xl lg:text-3xl font-bold text-slate-900 dark:text-white mb-2">
                                        {title}
                                    </h1>
                                    <p className="text-slate-600 dark:text-slate-400 leading-relaxed max-w-sm">
                                        {description}
                                    </p>
                                </div>
                            </div>

                            {/* Trust indicators */}
                            <div className="flex items-center gap-6 text-xs text-slate-500 dark:text-slate-400">
                                <div className="flex items-center gap-1">
                                    <div className="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                    Secure
                                </div>
                                <div className="flex items-center gap-1">
                                    <div className="w-2 h-2 bg-blue-500 rounded-full"></div>
                                    Protected
                                </div>
                                <div className="flex items-center gap-1">
                                    <div className="w-2 h-2 bg-purple-500 rounded-full"></div>
                                    Community
                                </div>
                            </div>
                        </div>

                        {/* Form content */}
                        <div className="space-y-6">
                            {children}
                        </div>
                    </div>

                    {/* Footer */}
                    <div className="mt-8 text-center text-sm text-slate-500 dark:text-slate-400">
                        <p>
                            Protected by EscrocAlert • Community-driven security
                        </p>
                    </div>
                </div>
            </div>
        </div>
    );
}
