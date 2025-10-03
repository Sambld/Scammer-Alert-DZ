import { Link } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { useI18n } from '@/hooks/use-i18n';
import { LanguageSwitcher } from '@/components/LanguageSwitcher';
import { ThemeToggle } from '@/components/theme-toggle';
import { Home, Search, FileText, BarChart3, Menu } from 'lucide-react';
import {
    Sheet,
    SheetContent,
    SheetDescription,
    SheetHeader,
    SheetTitle,
    SheetTrigger,
} from '@/components/ui/sheet';
import { useState } from 'react';

interface MobileMenuProps {
    auth: {
        user: {
            name: string;
        } | null;
    };
}

export function MobileMenu({ auth }: MobileMenuProps) {
    const [open, setOpen] = useState(false);
    const { t } = useI18n();

    return (
        <div className="lg:hidden">
            <Sheet open={open} onOpenChange={setOpen}>
                <SheetTrigger asChild>
                    <Button
                        variant="ghost"
                        size="icon"
                        className="relative"
                        aria-label="Toggle menu"
                    >
                        <Menu className="h-6 w-6" />
                    </Button>
                </SheetTrigger>
                <SheetContent side="right" className="w-full max-w-sm p-0">
                    <SheetHeader className="sr-only">
                        <SheetTitle>Navigation Menu</SheetTitle>
                        <SheetDescription>
                            Access navigation links and settings
                        </SheetDescription>
                    </SheetHeader>
                    <div className="flex flex-col h-full">
                        {/* Header */}
                        <div className="flex items-center space-x-3 p-6 border-b border-slate-200 dark:border-slate-700 shrink-0">
                            <div className="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-r from-red-500 to-orange-500 text-white font-bold">
                                E
                            </div>
                            <span className="font-bold text-slate-900 dark:text-white">
                                EscrocAlert
                            </span>
                        </div>

                        {/* Navigation */}
                        <nav className="flex-1 overflow-y-auto px-6 py-8">
                            <div className="space-y-4">
                                <Link
                                    href="/"
                                    className="flex items-center px-4 py-3 text-lg font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors"
                                    onClick={() => setOpen(false)}
                                >
                                    <Home className="w-5 h-5 mr-3" />
                                    {t('common:home')}
                                </Link>
                                <Link
                                    href="#search"
                                    className="flex items-center px-4 py-3 text-lg font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors"
                                    onClick={() => setOpen(false)}
                                >
                                    <Search className="w-5 h-5 mr-3" />
                                    {t('common:search')}
                                </Link>
                                <Link
                                    href="#report"
                                    className="flex items-center px-4 py-3 text-lg font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors"
                                    onClick={() => setOpen(false)}
                                >
                                    <FileText className="w-5 h-5 mr-3" />
                                    {t('common:report_scammer')}
                                </Link>
                                {auth.user && (
                                    <Link
                                        href={route('dashboard')}
                                        className="flex items-center px-4 py-3 text-lg font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors"
                                        onClick={() => setOpen(false)}
                                    >
                                        <BarChart3 className="w-5 h-5 mr-3" />
                                        {t('common:dashboard')}
                                    </Link>
                                )}
                            </div>

                            {/* Language Switcher */}
                            <div className="mt-8 px-4 space-y-4">
                                <div className="flex items-center justify-between">
                                    <span className="text-sm font-medium text-slate-700 dark:text-slate-300">
                                        {t('common:language')}
                                    </span>
                                    <LanguageSwitcher />
                                </div>
                                <div className="flex items-center justify-between">
                                    <span className="text-sm font-medium text-slate-700 dark:text-slate-300">
                                        {t('common:theme')}
                                    </span>
                                    <ThemeToggle />
                                </div>
                            </div>
                        </nav>

                        {/* Authentication buttons */}
                        <div className="p-6 border-t border-slate-200 dark:border-slate-700 shrink-0">
                            {auth.user ? (
                                <div className="space-y-3">
                                    <div className="text-sm text-slate-600 dark:text-slate-400">
                                        {t('common:welcome')}, {auth.user.name}
                                    </div>
                                    <Button
                                        asChild
                                        variant="outline"
                                        className="w-full"
                                        onClick={() => setOpen(false)}
                                    >
                                        <Link href={route('logout')} method="post">
                                            {t('common:logout')}
                                        </Link>
                                    </Button>
                                </div>
                            ) : (
                                <div className="space-y-3">
                                    <Button 
                                        asChild 
                                        variant="outline" 
                                        className="w-full"
                                        onClick={() => setOpen(false)}
                                    >
                                        <Link href={route('login')}>
                                            {t('common:login')}
                                        </Link>
                                    </Button>
                                    <Button 
                                        asChild 
                                        className="w-full bg-red-600 hover:bg-red-700"
                                        onClick={() => setOpen(false)}
                                    >
                                        <Link href={route('register')}>
                                            {t('common:register')}
                                        </Link>
                                    </Button>
                                </div>
                            )}
                        </div>
                    </div>
                </SheetContent>
            </Sheet>
        </div>
    );
}
