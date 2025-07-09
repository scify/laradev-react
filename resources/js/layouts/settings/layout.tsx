import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { cn } from '@/lib/utils';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/react';
import { type PropsWithChildren } from 'react';
import { useTranslations } from '@/hooks/use-translations';

export default function SettingsLayout({ children }: PropsWithChildren) {
	const { t } = useTranslations();

	const sidebarNavItems: NavItem[] = [
		{
			title: t('settings.profile.title'),
			href: '/settings/profile',
			icon: null,
		},
		{
			title: t('settings.password.title'),
			href: '/settings/password',
			icon: null,
		},
		{
			title: t('settings.appearance.title'),
			href: '/settings/appearance',
			icon: null,
		},
	];

	// When server-side rendering, we only render the layout on the client...
	if (typeof window === 'undefined') {
		return null;
	}

	const currentPath = window.location.pathname;

	return (
		<div className="px-4 py-6">
			<Heading title={t('settings.title')} description={t('settings.description')} />

			<div className="flex flex-col space-y-8 lg:flex-row lg:space-x-12 lg:space-y-0">
				<aside className="w-full max-w-xl lg:w-48">
					<nav className="flex flex-col space-x-0 space-y-1">
						{sidebarNavItems.map((item) => (
							<Button
								key={item.href}
								size="sm"
								variant="ghost"
								asChild
								className={cn('w-full justify-start', {
									'bg-muted': currentPath === item.href,
								})}
							>
								<Link href={item.href} prefetch>
									{t(item.title)}
								</Link>
							</Button>
						))}
					</nav>
				</aside>

				<Separator className="my-6 md:hidden" />

				<div className="flex-1 md:max-w-2xl">
					<section className="max-w-xl space-y-12">{children}</section>
				</div>
			</div>
		</div>
	);
}
