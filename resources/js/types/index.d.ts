import { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from 'lucide-vue-next';

export interface Auth {
  user: User;
}

export interface BreadcrumbItem {
  title: string;
  href: string;
}

export interface NavItem {
  title: string;
  href: NonNullable<InertiaLinkProps['href']>;
  icon?: LucideIcon;
  isActive?: boolean;
}

export type AppPageProps<
  T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
  name: string;
  quote: { message: string; author: string };
  auth: Auth;
  sidebarOpen: boolean;
};

export interface User {
  id: number;
  name: string;
  email: string;
  avatar?: string;
  email_verified_at: string | null;
  created_at: string;
  updated_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;

export interface Faq {
  id: number;
  question: string;
  answer: string;
}

export interface Teacher {
  id: number;
  authorisationCohort: string;
  business_email: string;
  business_phone: string;
  business_website: string;
  territoryOfOrigin: string;
  territoryOfResidence: string;
  description: string;
  gender: string;
  gives_video_lessons: boolean;
  instruments: string[];
  languagesSung: string[];
  languagesTeachesIn: string[];
  name: string;
  profile_image_path: string;
  qualification_string: string;
  teaches_at_cvi: boolean;
  tuitionLocations: string[];
  updateCohorts: string[];
}
