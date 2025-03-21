import { PageProps } from '@/types';
import { Head, Link } from '@inertiajs/react';
import AuthLayout from '@/layouts/auth-layout';

export default function Welcome({
	auth,
	laravelVersion,
	phpVersion,
}: PageProps<{ laravelVersion: string; phpVersion: string }>) {
	const canLogin = !auth.user;
	const canRegister = !auth.user;

	return (
		<AuthLayout
			title="Log in to your account"
			description="Enter your email and password below to log in"
		>
			<Head title="Welcome" />
			<div className="flex flex-col items-center justify-center bg-neutral-50 text-black dark:bg-neutral-900 dark:text-white">
				<div className="flex w-full max-w-2xl flex-col space-y-8 px-6 py-12 text-center">
					<h1 className="text-3xl font-bold text-neutral-900 dark:text-white">
						Welcome to Laradev-React!
					</h1>
					<p className="text-lg text-neutral-600 dark:text-neutral-300">
						Experience the power of Laravel and Inertia.js combined with React.
					</p>

					<div className="flex justify-center gap-4">
						{canLogin && (
							<Link
								href={route('login')}
								className="w-32 rounded-md bg-red-500 px-4 py-2 text-center text-white transition hover:bg-red-600 focus:outline-none focus:ring focus:ring-red-300"
							>
								Log in
							</Link>
						)}
						{canRegister && (
							<Link
								href={route('register')}
								className="w-32 rounded-md bg-neutral-700 px-4 py-2 text-center text-white transition hover:bg-neutral-800 focus:outline-none focus:ring focus:ring-neutral-400"
							>
								Register
							</Link>
						)}
					</div>

					<footer className="text-sm text-neutral-500 dark:text-neutral-400">
						Laravel v{laravelVersion} (PHP v{phpVersion})
					</footer>
				</div>
			</div>
		</AuthLayout>
	);
}
