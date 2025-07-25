#!/bin/bash

# Tunisia Governorate Cities WooCommerce Plugin - Deployment Script
# This script helps with Git operations and plugin packaging

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
PLUGIN_NAME="tunisia-governorate-cities"
VERSION="1.0.0"
REPO_URL="https://github.com/yourusername/tunisia-governorate-cities.git"

# Functions
print_header() {
    echo -e "${BLUE}================================${NC}"
    echo -e "${BLUE}  Tunisia Governorate Cities${NC}"
    echo -e "${BLUE}  WooCommerce Plugin${NC}"
    echo -e "${BLUE}  Deployment Script${NC}"
    echo -e "${BLUE}================================${NC}"
    echo
}

print_step() {
    echo -e "${YELLOW}[STEP]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

check_git() {
    if ! command -v git &> /dev/null; then
        print_error "Git is not installed. Please install Git first."
        exit 1
    fi
}

check_dependencies() {
    print_step "Checking dependencies..."
    
    # Check if we're in a Git repository
    if [ ! -d ".git" ]; then
        print_error "Not in a Git repository. Please run 'git init' first."
        exit 1
    fi
    
    print_success "Dependencies check passed"
}

init_git() {
    print_step "Initializing Git repository..."
    
    if [ ! -d ".git" ]; then
        git init
        print_success "Git repository initialized"
    else
        print_success "Git repository already exists"
    fi
}

setup_git_remote() {
    print_step "Setting up Git remote..."
    
    # Check if remote already exists
    if git remote get-url origin &> /dev/null; then
        print_success "Git remote 'origin' already configured"
    else
        echo -e "${YELLOW}Please enter your GitHub repository URL:${NC}"
        echo -e "Default: $REPO_URL"
        read -p "Repository URL: " repo_url
        repo_url=${repo_url:-$REPO_URL}
        
        git remote add origin "$repo_url"
        print_success "Git remote 'origin' configured"
    fi
}

build_assets() {
    print_step "Building assets..."
    
    # Check if Node.js is available
    if command -v npm &> /dev/null; then
        if [ -f "package.json" ]; then
            npm install
            npm run build
            print_success "Assets built successfully"
        else
            print_success "No package.json found, skipping asset build"
        fi
    else
        print_success "Node.js not available, skipping asset build"
    fi
}

create_initial_commit() {
    print_step "Creating initial commit..."
    
    # Add all files
    git add .
    
    # Check if there are changes to commit
    if git diff --cached --quiet; then
        print_success "No changes to commit"
    else
        git commit -m "Initial commit: Tunisia Governorate Cities WooCommerce Plugin v$VERSION
        
        Features:
        - Complete Tunisian governorates and cities data
        - Dynamic filtering functionality with AJAX
        - WooCommerce checkout integration
        - Billing and shipping address support
        - Responsive design and accessibility features
        - Form validation and error handling
        - Admin order display for governorate/city data"
        
        print_success "Initial commit created"
    fi
}

push_to_github() {
    print_step "Pushing to GitHub..."
    
    # Check if we have a remote configured
    if ! git remote get-url origin &> /dev/null; then
        print_error "No remote 'origin' configured. Please run setup first."
        exit 1
    fi
    
    # Push to main branch
    git push -u origin main
    
    print_success "Code pushed to GitHub successfully"
}

create_release() {
    print_step "Creating Git tag for release..."
    
    # Create annotated tag
    git tag -a "v$VERSION" -m "Release version $VERSION
    
    Features:
    - Complete Tunisian governorates and cities data
    - Dynamic filtering functionality with AJAX
    - WooCommerce checkout integration
    - Billing and shipping address support
    - Responsive design and accessibility features
    - Form validation and error handling
    - Admin order display for governorate/city data"
    
    # Push tag
    git push origin "v$VERSION"
    
    print_success "Release tag v$VERSION created and pushed"
}

create_plugin_package() {
    print_step "Creating plugin package..."
    
    # Create package directory
    package_dir="${PLUGIN_NAME}-v${VERSION}"
    mkdir -p "$package_dir"
    
    # Copy plugin files (excluding development files)
    cp -r assets "$package_dir/"
    cp -r languages "$package_dir/"
    cp tunisia-governorate-cities.php "$package_dir/"
    cp uninstall.php "$package_dir/"
    cp README.md "$package_dir/"
    cp LICENSE "$package_dir/"
    cp CHANGELOG.md "$package_dir/"
    
    # Create zip file
    zip -r "${PLUGIN_NAME}-v${VERSION}.zip" "$package_dir"
    
    # Clean up
    rm -rf "$package_dir"
    
    print_success "Plugin package created: ${PLUGIN_NAME}-v${VERSION}.zip"
}

show_help() {
    echo "Usage: $0 [OPTION]"
    echo
    echo "Options:"
    echo "  init      Initialize Git repository and setup remote"
    echo "  build     Build assets (CSS/JS minification)"
    echo "  commit    Create initial commit"
    echo "  push      Push code to GitHub"
    echo "  release   Create release tag and package"
    echo "  deploy    Full deployment (init + build + commit + push + release)"
    echo "  help      Show this help message"
    echo
    echo "Examples:"
    echo "  $0 init      # Initialize Git repository"
    echo "  $0 deploy    # Full deployment process"
    echo "  $0 release   # Create release package"
}

# Main script
main() {
    print_header
    
    case "${1:-help}" in
        "init")
            check_git
            init_git
            setup_git_remote
            ;;
        "build")
            build_assets
            ;;
        "commit")
            check_dependencies
            create_initial_commit
            ;;
        "push")
            check_dependencies
            push_to_github
            ;;
        "release")
            check_dependencies
            create_release
            create_plugin_package
            ;;
        "deploy")
            check_git
            init_git
            setup_git_remote
            build_assets
            create_initial_commit
            push_to_github
            create_release
            create_plugin_package
            print_success "Full deployment completed successfully!"
            ;;
        "help"|*)
            show_help
            ;;
    esac
}

# Run main function with all arguments
main "$@" 