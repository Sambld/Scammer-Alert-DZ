import { useState } from 'react';
import { Link } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { useI18n } from '@/hooks/use-i18n';
import { LanguageSwitcher } from '@/components/LanguageSwitcher';
import { Home, Search, FileText, BarChart3, X } from 'lucide-react';

interface MobileMenuProps {
    auth: {
        user: {
            name: string;
        } | null;
    };
}

export function MobileMenu({ auth }: MobileMenuProps) {
    const [isOpen, setIsOpen] = useState(false);
    const { t } = useI18n();

    return (
        <div className="lg:hidden">
            {/* Mobile menu button */}
            <Button
                variant="ghost"
                size="icon"
                onClick={() => setIsOpen(!isOpen)}
                className="relative z-50"
                aria-label="Toggle menu"
            >
                <div className="flex flex-col space-y-1">
                    <span
                        className={`block h-0.5 w-6 bg-slate-600 dark:bg-slate-300 transition-all duration-300 ${
                            isOpen ? 'rotate-45 translate-y-1.5' : ''
                        }`}
                    />
                    <span
                        className={`block h-0.5 w-6 bg-slate-600 dark:bg-slate-300 transition-opacity duration-300 ${
                            isOpen ? 'opacity-0' : ''
                        }`}
                    />
                    <span
                        className={`block h-0.5 w-6 bg-slate-600 dark:bg-slate-300 transition-all duration-300 ${
                            isOpen ? '-rotate-45 -translate-y-1.5' : ''
                        }`}
                    />
                </div>
            </Button>

            {/* Mobile menu overlay */}
            {isOpen && (
                <div className="fixed inset-0 z-40 bg-black/50" onClick={() => setIsOpen(false)} />
            )}

            {/* Mobile menu */}
            <div
                className={`fixed right-0 top-0 z-50 h-screen w-80 bg-white/95 backdrop-blur-lg dark:bg-slate-900/95 transform transition-transform duration-300 ease-in-out ${
                    isOpen ? 'translate-x-0' : 'translate-x-full'
                }`}
            >
                <div className="flex flex-col h-full">
                    {/* Header */}
                    <div className="flex items-center justify-between p-6 border-b border-slate-200 dark:border-slate-700">
                        <div className="flex items-center space-x-3">
                            <div className="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-r from-red-500 to-orange-500 text-white font-bold">
                                E
                            </div>
                            <span className="font-bold text-slate-900 dark:text-white">
                                EscrocAlert
                            </span>
                        </div>
                        <Button
                            variant="ghost"
                            size="icon"
                            onClick={() => setIsOpen(false)}
                        >
                            <X className="w-5 h-5" />
                        </Button>
                    </div>

                    {/* Navigation */}
                    <nav className="flex-1 px-6 py-8">
                        <div className="space-y-4">
                            <Link
                                href="/"
                                className="flex items-center px-4 py-3 text-lg font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors"
                                onClick={() => setIsOpen(false)}
                            >
                                <Home className="w-5 h-5 mr-3" />
                                {t('common:home')}
                            </Link>
                            <Link
                                href="#search"
                                className="flex items-center px-4 py-3 text-lg font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors"
                                onClick={() => setIsOpen(false)}
                            >
                                <Search className="w-5 h-5 mr-3" />
                                {t('common:search')}
                            </Link>
                            <Link
                                href="#report"
                                className="flex items-center px-4 py-3 text-lg font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors"
                                onClick={() => setIsOpen(false)}
                            >
                                <FileText className="w-5 h-5 mr-3" />
                                {t('common:report_scammer')}
                            </Link>
                            {auth.user && (
                                <Link
                                    href={route('dashboard')}
                                    className="flex items-center px-4 py-3 text-lg font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors"
                                    onClick={() => setIsOpen(false)}
                                >
                                    <BarChart3 className="w-5 h-5 mr-3" />
                                    {t('common:dashboard')}
                                </Link>
                            )}
                        </div>

                        {/* Language Switcher */}
                        <div className="mt-8 px-4">
                            <LanguageSwitcher />
                        </div>
                    </nav>

                    {/* Authentication buttons */}
                    <div className="p-6 border-t border-slate-200 dark:border-slate-700">
                        {auth.user ? (
                            <div className="space-y-3">
                                <div className="text-sm text-slate-600 dark:text-slate-400">
                                    {t('common:welcome')}, {auth.user.name}
                                </div>
                                <Button
                                    asChild
                                    variant="outline"
                                    className="w-full"
                                >
                                    <Link href={route('logout')} method="post">
                                        {t('common:logout')}
                                    </Link>
                                </Button>
                            </div>
                        ) : (
                            <div className="space-y-3">
                                <Button asChild variant="outline" className="w-full">
                                    <Link href={route('login')}>
                                        {t('common:login')}
                                    </Link>
                                </Button>
                                <Button asChild className="w-full bg-gradient-to-r from-red-500 to-orange-500">
                                    <Link href={route('register')}>
                                        {t('common:register')}
                                    </Link>
                                </Button>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
}
