import { NavFooter } from '@/components/nav-footer';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import {
	Sidebar,
	SidebarContent,
	SidebarFooter,
	SidebarHeader,
	SidebarMenu,
	SidebarMenuButton,
	SidebarMenuItem,
} from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { BookOpen, Folder, LayoutGrid, Users, UserPlus } from 'lucide-react';
import AppLogo from './app-logo';
import { Auth } from '@/types';
import { useTranslations } from '@/hooks/use-translations';

export function AppSidebar() {
	const { auth } = usePage().props as unknown as { auth: Auth };
	const { t } = useTranslations();

	const mainNavItems: NavItem[] = [
		{
			title: 'Dashboard',
			href: '/dashboard',
			icon: LayoutGrid,
		},
		auth.user?.can.view_users
			? {
					title: 'Users',
					children: [
						{
							title: t('users.index_page_title'),
							href: route('users.index'),
							icon: Users,
						},
						auth.user?.can.create_users
							? {
									title: t('users.actions.new'),
									href: route('users.create'),
									icon: UserPlus,
								}
							: null,
					].filter(Boolean) as NavItem[],
				}
			: null,
	].filter(Boolean) as NavItem[];

	const footerNavItems: NavItem[] = [
		{
			title: 'Repository',
			href: 'https://github.com/laravel/react-starter-kit',
			icon: Folder,
		},
		{
			title: 'Documentation',
			href: 'https://laravel.com/docs/starter-kits',
			icon: BookOpen,
		},
	];

	return (
		<Sidebar collapsible="icon" variant="inset">
			<SidebarHeader>
				<SidebarMenu>
					<SidebarMenuItem>
						<SidebarMenuButton size="lg" asChild>
							<Link href="/dashboard" prefetch>
								<AppLogo />
							</Link>
						</SidebarMenuButton>
					</SidebarMenuItem>
				</SidebarMenu>
			</SidebarHeader>

			<SidebarContent>
				<NavMain items={mainNavItems} />
			</SidebarContent>

			<SidebarFooter>
				<NavFooter items={footerNavItems} className="mt-auto" />
				<NavUser />
			</SidebarFooter>
		</Sidebar>
	);
}
