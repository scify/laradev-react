import {
	SidebarMenu,
	SidebarMenuItem,
	SidebarGroup,
	SidebarGroupLabel,
} from '@/components/ui/sidebar';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { type NavItem as NavItemType } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { ChevronDown } from 'lucide-react';
import { cn } from '@/lib/utils';
import { useState, useEffect } from 'react';

interface NavMainProps {
	items: NavItemType[];
}

export function NavMain({ items }: NavMainProps) {
	const { url } = usePage();
	const [openGroups, setOpenGroups] = useState<number[]>(() => {
		// First check which groups should be open based on current URL
		const urlMatchIndexes = items
			.map((item, index) => (item.children?.some((child) => child.href === url) ? index : -1))
			.filter((index) => index !== -1);

		// Then merge with saved state
		const saved = localStorage.getItem('sidebarOpenGroups');
		const savedGroups = saved ? (JSON.parse(saved) as number[]) : [];
		return [...new Set([...savedGroups, ...urlMatchIndexes])];
	});

	useEffect(() => {
		localStorage.setItem('sidebarOpenGroups', JSON.stringify(openGroups));
	}, [openGroups]);

	// Only handle child clicks for highlighting
	const handleChildClick = (parentIndex: number) => {
		if (!openGroups.includes(parentIndex)) {
			setOpenGroups([...openGroups, parentIndex]);
		}
	};

	return (
		<SidebarMenu>
			{items.map((item, index) =>
				item.children ? (
					<Collapsible
						key={index}
						className="group/collapsible"
						open={openGroups.includes(index)}
						onOpenChange={(open) => {
							setOpenGroups(
								open
									? [...openGroups, index]
									: openGroups.filter((i) => i !== index)
							);
						}}
					>
						<SidebarGroup>
							<SidebarGroupLabel asChild>
								<CollapsibleTrigger className="text-sidebar-foreground/100 hover:text-sidebar-foreground/70 flex items-center px-2 hover:cursor-pointer">
									{item.icon && <item.icon className="mr-2 h-4 w-4" />}
									{item.title}
									<ChevronDown className="ml-auto h-4 w-4 transition-transform group-data-[state=open]/collapsible:rotate-180" />
								</CollapsibleTrigger>
							</SidebarGroupLabel>
							<CollapsibleContent className="px-2">
								{item.children.map((child, childIndex) => (
									<SidebarMenuItem key={childIndex}>
										<Link
											href={child.href!}
											onClick={() => handleChildClick(index)}
											className={cn(
												'mb-1 flex items-center rounded-md px-3 py-1',
												((child.href === url ||
													child.href?.endsWith(url)) ??
													false)
													? 'bg-sidebar-foreground/20 text-sidebar-background'
													: 'hover:bg-sidebar-foreground/10'
											)}
										>
											{child.icon && <child.icon className="mr-2 h-4 w-4" />}
											{child.title}
										</Link>
									</SidebarMenuItem>
								))}
							</CollapsibleContent>
						</SidebarGroup>
					</Collapsible>
				) : (
					<SidebarMenuItem key={index} className="px-2">
						<Link
							href={item.href!}
							className={cn(
								'flex items-center rounded-md px-2 py-1',
								((item.href === url || item.href?.endsWith(url)) ?? false)
									? 'bg-sidebar-foreground/20 text-sidebar-background'
									: 'hover:bg-sidebar-foreground/10'
							)}
						>
							{item.icon && <item.icon className="mr-2 h-4 w-4" />}
							{item.title}
						</Link>
					</SidebarMenuItem>
				)
			)}
		</SidebarMenu>
	);
}
