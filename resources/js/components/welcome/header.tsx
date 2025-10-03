import { LanguageSwitcher } from '@/components/LanguageSwitcher';
import { MobileMenu } from '@/components/mobile-menu';
import { ThemeToggle } from '@/components/theme-toggle';
import { Button } from '@/components/ui/button';
import { type SharedData } from '@/types';
import { Link } from '@inertiajs/react';

interface HeaderProps {
    auth: SharedData['auth'];
    t: (key: string) => string;
}

export function Header({ auth, t }: HeaderProps) {
    return (
        <header className="relative z-50 w-full border-b border-slate-200/50 bg-white/80 backdrop-blur-md dark:border-slate-700/50 dark:bg-slate-900/80">
            <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <nav className="flex h-16 items-center justify-between">
                    <div className="flex items-center space-x-3">
                        <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-r from-red-500 to-orange-500 text-lg font-bold text-white">
                            E
                        </div>
                        <span className="text-xl font-bold text-slate-900 dark:text-white">EscrocAlert</span>
                    </div>

                    {/* Desktop Navigation */}
                    <div className="hidden items-center gap-4 lg:flex">
                        <ThemeToggle />
                        <LanguageSwitcher />
                        {auth.user ? (
                            <Button asChild variant="default">
                                <Link href={route('dashboard')}>{t('common:dashboard')}</Link>
                            </Button>
                        ) : (
                            <div className="flex items-center gap-2">
                                <Button asChild variant="ghost">
                                    <Link href={route('login')}>{t('common:login')}</Link>
                                </Button>
                                <Button asChild variant="default">
                                    <Link href={route('register')}>{t('common:register')}</Link>
                                </Button>
                            </div>
                        )}
                    </div>

                    {/* Mobile Navigation - Theme Toggle and Menu */}
                    <div className="flex items-center gap-2 lg:hidden">
                        <MobileMenu auth={auth} />
                    </div>
                </nav>
            </div>
        </header>
    );
}
