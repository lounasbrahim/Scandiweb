import React, { useState, useEffect } from 'react';
import { useParams } from 'react-router-dom';
import { useQuery } from '@apollo/client';
import parse from 'html-react-parser';
import { GET_PRODUCT_BY_ID, type GetProductByIdData } from '../graphQl/queries';
import type { CartItem } from '../types';

interface SingleProductProps {
  addTocart: (item: CartItem) => void;
  openCart: () => void;
}

export default function SingleProduct({ addTocart, openCart }: SingleProductProps) {
  const { id } = useParams<{ id: string }>();
  const [selectedAttributes, setSelectedAttributes] = useState<Record<string, string>>({});
  const [mainImageIndex, setMainImageIndex] = useState<number>(0);
  const [galleryScrollIndex, setGalleryScrollIndex] = useState<number>(0);

  const { loading, error, data } = useQuery<GetProductByIdData>(GET_PRODUCT_BY_ID, {
    variables: { id },
    skip: !id,
  });

  const product = data?.product;

  useEffect(() => {
    if (product?.gallery?.length) {
      setMainImageIndex(0);
      setGalleryScrollIndex(0);
    }
  }, [product]);

  const handleAttributeSelect = (attrName: string, value: string): void => {
    setSelectedAttributes((prev) => ({ ...prev, [attrName]: value }));
  };

  const handleAddToCart = (): void => {
    if (!product) return;

    const cartItem: CartItem = {
      name: product.name,
      price: product.price,
      image: product.gallery[0],
      selectedAttributes,
      attributes: product.attributes.map((attr) => ({
        id: attr.id,
        name: attr.name,
        type: attr.type,
        items: attr.items,
      })),
      quantity: 1,
    };

    addTocart(cartItem);
    openCart();
  };

  const allSelected = product?.attributes?.every((attr) => selectedAttributes[attr.name]);
  const canAddToCart = Boolean(allSelected && product?.in_stock);

  const nextImage = (): void => {
    if (product?.gallery?.length) {
      setMainImageIndex((prev) => (prev + 1) % product.gallery.length);
    }
  };

  const prevImage = (): void => {
    if (product?.gallery?.length) {
      setMainImageIndex((prev) => (prev - 1 + product.gallery.length) % product.gallery.length);
    }
  };

  const scrollUp = (): void => {
    setGalleryScrollIndex((prev) => Math.max(prev - 1, 0));
  };

  const scrollDown = (): void => {
    if (!product) return;
    setGalleryScrollIndex((prev) => Math.min(prev + 1, product.gallery.length - 4));
  };

  if (loading) return <p className="p-4">Loading product...</p>;
  if (error || !product) return <p className="p-4 text-red-500">Product not found.</p>;

  const visibleThumbnails = product.gallery.slice(galleryScrollIndex, galleryScrollIndex + 4);

  return (
    <div data-testid="product-gallery" className="flex flex-col md:flex-row gap-4 sm:gap-6 p-2 sm:p-4">

      <div className="flex flex-row md:flex-col gap-2 order-2 md:order-1 overflow-x-auto md:overflow-visible">
        {product.gallery.length > 4 && (
          <button onClick={scrollUp} className="hidden md:block text-center py-1">&#8743;</button>
        )}
        {visibleThumbnails.map((img, index) => (
          <img
            key={index}
            src={img}
            alt={`gallery-${galleryScrollIndex + index}`}
            onClick={() => setMainImageIndex(galleryScrollIndex + index)}
            className={`w-16 h-16 sm:w-20 sm:h-20 flex-shrink-0 object-cover cursor-pointer border ${
              product.gallery[mainImageIndex] === img ? 'border-black border-2' : 'border-gray-200'
            }`}
          />
        ))}
        {product.gallery.length > 4 && (
          <button onClick={scrollDown} className="hidden md:block text-center py-1">&#8744;</button>
        )}
      </div>

      <div className="relative flex-1 order-1 md:order-2">
        <img
          src={product.gallery[mainImageIndex]}
          alt="main"
          className="w-full max-h-64 sm:max-h-96 md:max-h-[500px] object-contain"
        />
        {product.gallery.length > 1 && (
          <button
            onClick={prevImage}
            className="absolute left-2 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-70 text-white px-2 py-1 sm:p-2 text-sm sm:text-base"
          >
            &#x3c;
          </button>
        )}
        {product.gallery.length > 1 && (
          <button
            onClick={nextImage}
            className="absolute right-2 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-70 text-white px-2 py-1 sm:p-2 text-sm sm:text-base"
          >
            &#x3e;
          </button>
        )}
      </div>

      <div className="order-3 w-full md:w-72 lg:w-80 flex-shrink-0">
        <h1 className="text-xl sm:text-2xl font-semibold my-3 sm:my-4">{product.name}</h1>

        {product.attributes?.map((attr) => {
          const attrKey = attr.name.toLowerCase().replace(/\s+/g, '-');
          const isSwatch = attr.type === 'swatch';
          return (
            <div key={attr.name} className="mb-3 sm:mb-4" data-testid={`product-attribute-${attrKey}`}>
              <h2 className="font-medium text-sm uppercase mb-2">{attr.name}:</h2>
              <div className="flex gap-2 flex-wrap">
                {attr.items.map((item) => {
                  const isSelected = selectedAttributes[attr.name] === item.value;
                  return (
                    <button
                      key={item.id}
                      onClick={() => handleAttributeSelect(attr.name, item.value)}
                      className={`border cursor-pointer transition
                        ${isSelected ? '' : 'border-gray-300'}
                        ${isSwatch ? 'w-8 h-8' : 'px-3 sm:px-5 py-2 sm:py-3 text-sm'}
                        ${!isSwatch && isSelected ? 'bg-black text-white' : ''}
                        ${!isSwatch && !isSelected ? 'bg-white text-black' : ''}`}
                      style={isSwatch ? { backgroundColor: item.value } : {}}
                      data-testid={`product-attribute-${attrKey}-${item.value}`}
                    >
                      {!isSwatch && item.displayValue}
                    </button>
                  );
                })}
              </div>
            </div>
          );
        })}

        <div className="my-3 sm:my-4">
          <h3 className="font-medium text-sm uppercase mb-2">Price:</h3>
          <p className="text-lg sm:text-xl font-bold">${product.price?.toFixed(2)}</p>
        </div>

        <button
          onClick={handleAddToCart}
          data-testid="add-to-cart"
          disabled={!canAddToCart}
          className="w-full px-6 py-3 bg-[#5ece7b] text-white text-sm sm:text-base hover:bg-green-500 disabled:bg-gray-400 transition uppercase"
        >
          Add to Cart
        </button>

        <div className="mt-4 sm:mt-6 prose prose-sm sm:prose max-w-none" data-testid="product-description">
          {parse(product.description ?? '')}
        </div>
      </div>
    </div>
  );
}
