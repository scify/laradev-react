import AuthLayoutTemplate from '@/layouts/auth/auth-card-layout';

export default function AuthLayout({
	children,
	title,
	description,
	...props
}: {
	children: // eslint-disable-next-line no-undef
	React.ReactNode;
	title: string;
	description: string;
}) {
	return (
		<AuthLayoutTemplate title={title} description={description} {...props}>
			{children}
		</AuthLayoutTemplate>
	);
}
