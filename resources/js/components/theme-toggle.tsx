import { Moon, Sun, Monitor } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { useAppearance } from '@/hooks/use-appearance';
import { useState } from 'react';

export function ThemeToggle() {
    const { appearance, updateAppearance } = useAppearance();
    const [isOpen, setIsOpen] = useState(false);

    const themes = [
        { value: 'light' as const, label: 'Light', icon: Sun },
        { value: 'dark' as const, label: 'Dark', icon: Moon },
        { value: 'system' as const, label: 'System', icon: Monitor }
    ];

    const currentTheme = themes.find(theme => theme.value === appearance) || themes[2];

    return (
        <div className="relative">
            <Button
                variant="ghost"
                size="icon"
                onClick={() => setIsOpen(!isOpen)}
                className="w-9 h-9"
                aria-label="Toggle theme"
            >
                <currentTheme.icon className="w-4 h-4" />
            </Button>

            {isOpen && (
                <>
                    {/* Backdrop */}
                    <div 
                        className="fixed inset-0 z-40" 
                        onClick={() => setIsOpen(false)}
                    />
                    
                    {/* Dropdown */}
                    <div className="absolute right-0 top-full mt-2 w-40 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg shadow-lg z-50 py-1">
                        {themes.map((theme) => (
                            <button
                                key={theme.value}
                                onClick={() => {
                                    updateAppearance(theme.value);
                                    setIsOpen(false);
                                }}
                                className={`w-full flex items-center px-3 py-2 text-sm hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors ${
                                    appearance === theme.value ? 'bg-slate-100 dark:bg-slate-700' : ''
                                }`}
                            >
                                <theme.icon className="w-4 h-4 mr-3" />
                                {theme.label}
                            </button>
                        ))}
                    </div>
                </>
            )}
        </div>
    );
}
