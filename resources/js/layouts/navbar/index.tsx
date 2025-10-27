import AppLogo from '@/components/app-logo';
import Appearance from '@/components/appearance';
import CourseCart from '@/components/course-cart';
import Language from '@/components/language';
import Notification from '@/components/notification';
import ProfileToggle from '@/components/profile-toggle';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { ScrollArea } from '@/components/ui/scroll-area';
import { useAuth } from '@/hooks/use-auth';
import useScreen from '@/hooks/use-screen';
import { cn } from '@/lib/utils';
import { SharedData } from '@/types/global';
import { Link, usePage } from '@inertiajs/react';
import { ChevronDown, Menu, X } from 'lucide-react';
import { Fragment, useEffect, useState } from 'react';

interface NavbarProps {
   heightCover?: boolean;
   customizable?: boolean;
}

const Navbar = ({ heightCover = true, customizable = true }: NavbarProps) => {
   const { props } = usePage<SharedData>();
   const { ziggy, navbar, translate } = props;
   const { isAdmin, isLoggedIn } = useAuth();
   const [isSticky, setIsSticky] = useState(false);
   const [isMenuOpen, setIsMenuOpen] = useState(false);
   const { screen } = useScreen();

   useEffect(() => {
      const handleScroll = () => {
         const scrollPosition = window.scrollY;
         if (scrollPosition > 100) {
            setIsSticky(true);
         } else {
            setIsSticky(false);
         }
      };

      window.addEventListener('scroll', handleScroll);

      return () => {
         window.removeEventListener('scroll', handleScroll);
      };
   }, []);

   const renderNavItems = (item: NavbarItem) => {
      switch (item.type) {
         case 'url':
            return (
               <Link key={item.id} href={item.value || ''} className="text-sm font-normal">
                  {item.title}
               </Link>
            );

         case 'dropdown':
            return (
               <DropdownMenu key={item.id}>
                  <DropdownMenuTrigger className="flex cursor-pointer items-center gap-1 text-sm">
                     {item.title}
                     <ChevronDown className="ml-1 h-4 w-4" />
                  </DropdownMenuTrigger>
                  <DropdownMenuContent align="start" className="min-w-20">
                     {item.items &&
                        Array.isArray(item.items) &&
                        item.items.map((subItem: any, idx: number) => (
                           <DropdownMenuItem key={idx} asChild className="cursor-pointer px-5">
                              <Link href={subItem.url || ''}>{subItem.title}</Link>
                           </DropdownMenuItem>
                        ))}
                  </DropdownMenuContent>
               </DropdownMenu>
            );

         default:
            return null;
      }
   };

   const sortedItems = navbar.navbar_items.sort((a, b) => a.sort - b.sort);
   const customizeLink = props.customize ? ziggy.location : '?customize=true';

   return (
      <>
         <div className={cn('fixed top-0 z-30 w-full', isMenuOpen && 'bg-background')}>
            <div
               className={cn(
                  'container mt-0 flex h-[72px] items-center justify-between gap-1 !px-4 transition-all duration-200 md:gap-6',
                  isSticky && 'bg-background shadow-card mx-auto mt-4 h-16 w-full rounded-2xl md:!max-w-6xl',
                  screen < 768 && 'mt-0 h-[72px] rounded-none',
               )}
            >
               {/* Logo */}
               <div className="hidden items-center gap-10 md:flex">
                  <Link href="/">
                     <AppLogo />
                  </Link>

                  {/* Desktop Navigation */}
                  <div className="flex gap-4 md:items-center">
                     {sortedItems.map((item) => (
                        <Fragment key={item.id}>{renderNavItems(item)}</Fragment>
                     ))}
                  </div>
               </div>
               {/* Mobile menu button */}
               <Button size="icon" variant="secondary" className="md:hidden" onClick={() => setIsMenuOpen(!isMenuOpen)}>
                  {isMenuOpen ? <X className="h-6 w-6" /> : <Menu className="h-6 w-6" />}
               </Button>

               <div className="flex items-center gap-2">
                  {customizable && isAdmin && (
                     <Button asChild variant="outline" className="hidden text-sm font-normal sm:block">
                        <Link href={customizeLink}>{props.customize ? 'Back' : 'Customize'}</Link>
                     </Button>
                  )}

                  {sortedItems.map((item) => {
                     if (item.slug === 'theme') {
                        return <Appearance />;
                     } else if (item.slug === 'language') {
                        return <Language />;
                     } else {
                        return null;
                     }
                  })}

                  {isLoggedIn ? (
                     sortedItems.map((item) => {
                        if (item.slug === 'notification') {
                           return <Notification />;
                        } else if (item.slug === 'profile') {
                           return <ProfileToggle />;
                        } else if (item.slug === 'cart') {
                           return <CourseCart />;
                        } else {
                           return null;
                        }
                     })
                  ) : (
                     <div className="hidden space-x-2 sm:block">
                        <Button asChild variant="outline" className="">
                           <Link href={route('register')}>{translate.button.sign_up}</Link>
                        </Button>
                        <Button asChild className="">
                           <Link href={route('login')}>{translate.button.log_in}</Link>
                        </Button>
                     </div>
                  )}
               </div>
            </div>

            {/* Mobile Menu */}
            {isMenuOpen && (
               <ScrollArea className="bg-background h-[calc(100vh-72px)] border-t md:hidden">
                  <div className="flex flex-col space-y-4 px-6 py-4">
                     <Link href="/">
                        <AppLogo />
                     </Link>

                     {sortedItems.map((item) => (
                        <Fragment key={item.id}>{renderNavItems(item)}</Fragment>
                     ))}

                     {customizable && isAdmin && (
                        <Button asChild variant="outline" className="text-sm font-normal">
                           <Link href={customizeLink}>{props.customize ? 'Back' : 'Customize'}</Link>
                        </Button>
                     )}

                     {!isLoggedIn && (
                        <div className="block space-y-2 sm:hidden">
                           <Button asChild variant="outline" className="w-full rounded-sm shadow-none sm:px-5 md:h-10">
                              <Link href={route('register')}>{translate.button.sign_up}</Link>
                           </Button>
                           <Button asChild className="w-full rounded-sm shadow-none sm:px-5 md:h-10">
                              <Link href={route('login')}>{translate.button.log_in}</Link>
                           </Button>
                        </div>
                     )}
                  </div>
               </ScrollArea>
            )}
         </div>

         {heightCover && <div className="relative z-20 h-[72px] bg-transparent" />}
      </>
   );
};

export default Navbar;
