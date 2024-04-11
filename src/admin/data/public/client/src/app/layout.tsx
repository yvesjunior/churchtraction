import type { Metadata } from "next";
import { Inter } from "next/font/google";
import "./globals.css";
import SideNavBar from "@/components/SideNavBar";
import Header from "@/components/Header";
import { SideNavProvider } from "@/components/SideNavContext";

const inter = Inter({ subsets: ["latin"] });

export const metadata: Metadata = {
  title: "Create Next App",
  description: "Generated by create next app",
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  
  return (
    <html lang="en">
      <body className={inter.className}>
      <SideNavProvider>
        <div className='flex'>
          <SideNavBar />
          <div className="w-full h-screen overflow-auto bg-[#F9FAFB]">
            <Header />
            {children}
          </div>
        </div>
        </SideNavProvider>
      </body>
    </html>
  );
}
